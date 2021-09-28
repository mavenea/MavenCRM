<?php
/*************************************
 * SPDX-FileCopyrightText: 2009-2020 Vtenext S.r.l. <info@vtenext.com> 
 * SPDX-License-Identifier: AGPL-3.0-only  
 ************************************/

require_once 'include/Webservices/Create.php';
require_once 'include/Webservices/Update.php';
require_once 'include/Webservices/Delete.php';
require_once 'include/Webservices/Revise.php';
require_once 'include/Webservices/Retrieve.php';
require_once 'include/Webservices/DataTransform.php';
require_once 'vtlib/Vtecrm/Utils.php';//crmv@207871
require_once 'include/utils/ConfigReader.php';
require_once 'data/CRMEntity.php';
require_once 'include/QueryGenerator/QueryGenerator.php';
require_once 'modules/Import/resources/Utils.php';
require_once 'modules/Import/controllers/Import_Lock_Controller.php';
require_once 'modules/Import/controllers/Import_Queue_Controller.php';
require_once 'vtlib/Vtecrm/Mailer.php';//crmv@207871
require_once('include/utils/VTEProperties.php');


class Import_Data_Controller {

	var $id;
	var $user;
	var $module;
	var $fieldMapping;
	var $mergeType;
	var $mergeFields;
	var $defaultValues;
	var $fieldsFormats; // crmv@83878
	var $importedRecordInfo = array();
	var $batchImport = true;

	static $IMPORT_RECORD_NONE = 0;
	static $IMPORT_RECORD_CREATED = 1;
	static $IMPORT_RECORD_SKIPPED = 2;
	static $IMPORT_RECORD_UPDATED = 3;
	static $IMPORT_RECORD_MERGED = 4;
	static $IMPORT_RECORD_FAILED = 5;

	public function __construct($importInfo, $user) {
		$this->id = $importInfo['id'];
		$this->module = $importInfo['module'];
		$this->fieldMapping = $importInfo['field_mapping'];
		$this->mergeType = $importInfo['merge_type'];
		$this->mergeFields = $importInfo['merge_fields'];
		$this->defaultValues = $importInfo['default_values'];
		$this->fieldsFormats = $importInfo['fields_formats']; // crmv@83878
		$this->user = $user;
	}

	public function getDefaultFieldValues($moduleMeta) {
		static $cachedDefaultValues = array();

		if (isset($cachedDefaultValues[$this->module])) {
			return $cachedDefaultValues[$this->module];
		}

		$defaultValues = array();
		if (!empty($this->defaultValues)) {
			if(!is_array($this->defaultValues)) {
				$this->defaultValues = Zend_Json::decode($this->defaultValues);
			}
			if($this->defaultValues != null) {
				$defaultValues = $this->defaultValues;
			}
		}
		$moduleFields = $moduleMeta->getModuleFields();
		$moduleMandatoryFields = $moduleMeta->getMandatoryFields();
		foreach ($moduleMandatoryFields as $mandatoryFieldName) {
			if (empty($defaultValues[$mandatoryFieldName])) {
				$fieldInstance = $moduleFields[$mandatoryFieldName];
				if($fieldInstance->getFieldDataType() == 'owner') {
					$defaultValues[$mandatoryFieldName] = $this->user->id;
				} elseif($fieldInstance->getFieldDataType() != 'datetime'
						&& $fieldInstance->getFieldDataType() != 'date'
						&& $fieldInstance->getFieldDataType() != 'time') {
					$defaultValues[$mandatoryFieldName] = '????';
				}
			}
		}
		foreach ($moduleFields as $fieldName => $fieldInstance) {
			$fieldDefaultValue = $fieldInstance->getDefault();
			if(empty ($defaultValues[$fieldName])) {
				if($fieldInstance->getUIType() == '52') {
					$defaultValues[$fieldName] = $this->user->id;
				} elseif(!empty($fieldDefaultValue)) {
					$defaultValues[$fieldName] = $fieldDefaultValue;
				}
			}
		}
		$cachedDefaultValues[$this->module] = $defaultValues;
		return $defaultValues;
	}

	public function import() {
		if(!$this->initializeImport()) return false;
		$this->importData();
		$this->finishImport();
	}

	public function importData() {
		$this->createRecords();
		$this->updateModuleSequenceNumber();
	}

	public function initializeImport() {
		$lockInfo = Import_Lock_Controller::isLockedForModule($this->module);
		//crmv@36796
		if ($lockInfo != null && strtotime($lockInfo['locked_since']) <= strtotime('-1 hour')){
			Import_Lock_Controller::unLock($lockInfo['userid'],$this->module);
			$lockInfo = null;
		}
		//crmv@36796 e
		if ($lockInfo != null) {
			if($lockInfo['userid'] != $this->user->id) {
				Import_Utils::showImportLockedError($lockInfo);
				return false;
			} else {
				return true;
			}
		} else {
			Import_Lock_Controller::lock($this->id, $this->module, $this->user);
			return true;
		}
	}

	public function finishImport() {
		Import_Lock_Controller::unLock($this->user, $this->module);
		Import_Queue_Controller::remove($this->id);
	}

	public function updateModuleSequenceNumber() {
		$moduleName = $this->module;
		$focus = CRMEntity::getInstance($moduleName);
		$focus->updateMissingSeqNumber($moduleName);
	}

	public function updateImportStatus($entryId, $entityInfo) {
		$adb = PearDatabase::getInstance();
		$recordId = null;
		if (!empty($entityInfo['id'])) {
			$entityIdComponents = vtws_getIdComponents($entityInfo['id']);
			$recordId = $entityIdComponents[1];
		}
		$adb->pquery('UPDATE ' . Import_Utils::getDbTableName($this->user) . ' SET status=?, recordid=? WHERE id=?',
				array($entityInfo['status'], $recordId, $entryId));
	}

	// crmv@90216
	public function createRecords() {
		$adb = PearDatabase::getInstance();
		$moduleName = $this->module;

		$focus = CRMEntity::getInstance($moduleName);
		$moduleHandler = vtws_getModuleHandlerFromName($moduleName, $this->user);
		$moduleMeta = $moduleHandler->getMeta();
		$moduleObjectId = $moduleMeta->getEntityId();
		$moduleFields = $moduleMeta->getModuleFields();

		$tableName = Import_Utils::getDbTableName($this->user);
		$sql = 'SELECT * FROM ' . $tableName . ' WHERE status = '. Import_Data_Controller::$IMPORT_RECORD_NONE;

		if($this->batchImport) {
			// crmv@200009
			$VTEP = VTEProperties::getInstance();
			$importBatchLimit = $VTEP->getProperty('modules.import.import_batch_Limit');
			// crmv@200009e
		}
		if ($importBatchLimit > 0) {
			$result = $adb->limitQuery($sql,0,$importBatchLimit);
		} else {
			$result = $adb->query($sql);
		}
		$numberOfRecords = $adb->num_rows($result);

		if ($numberOfRecords <= 0) {
			return;
		}

		$fieldMapping = $this->fieldMapping;
		$fieldColumnMapping = $moduleMeta->getFieldColumnMapping();

		for ($i = 0; $i < $numberOfRecords; ++$i) {
			$row = $adb->raw_query_result_rowdata($result, $i);
			$rowId = $row['id'];
			$entityInfo = null;
			$fieldData = array();
			foreach ($fieldMapping as $fieldName => $index) {
				$fieldData[$fieldName] = $row[$fieldName];
			}

			$mergeType = $this->mergeType;
			$createRecord = false;

			if(method_exists($focus, 'importRecord')) {
				$entityInfo = $focus->importRecord($this, $fieldData);
			} else {
				if (!empty($mergeType) && $mergeType != Import_Utils::$AUTO_MERGE_NONE) {

					$queryGenerator = QueryGenerator::getInstance($moduleName, $this->user); // crmv@139359
					$queryGenerator->initForDefaultCustomView();
					$fieldsList = array('id');
					$queryGenerator->setFields($fieldsList);

					$mergeFields = $this->mergeFields;
					foreach ($mergeFields as $index => $mergeField) {
						if ($queryGenerator->getconditionInstanceCount() > 0) { //crmv@42329
							$queryGenerator->addConditionGlue(QueryGenerator::$AND);
						}
						$comparisonValue = $fieldData[$mergeField];
						$fieldInstance = $moduleFields[$mergeField];
						if ($fieldInstance->getFieldDataType() == 'owner') {
							$userId = getUserId_Ol($comparisonValue);
							$comparisonValue = getUserFullName($userId);
						}
						if ($fieldInstance->getFieldDataType() == 'reference') {
							if(strpos($comparisonValue, '::::') > 0) {
								$referenceFileValueComponents = explode('::::', $comparisonValue);
							} else {
								$referenceFileValueComponents = explode(':::', $comparisonValue);
							}
							if (count($referenceFileValueComponents) > 1) {
								$comparisonValue = trim($referenceFileValueComponents[1]);
							}
						}
						$queryGenerator->addCondition($mergeField, $comparisonValue, 'e');
					}
					$query = $queryGenerator->getQuery();
					$duplicatesResult = $adb->query($query);
					$noOfDuplicates = $adb->num_rows($duplicatesResult);

					if ($noOfDuplicates > 0) {
						if ($mergeType == Import_Utils::$AUTO_MERGE_IGNORE) {
							$entityInfo['status'] = self::$IMPORT_RECORD_SKIPPED;
						} elseif ($mergeType == Import_Utils::$AUTO_MERGE_OVERWRITE ||
								$mergeType == Import_Utils::$AUTO_MERGE_MERGEFIELDS) {

							for ($index = 0; $index < $noOfDuplicates - 1; ++$index) {
								$duplicateRecordId = $adb->query_result($duplicatesResult, $index, $fieldColumnMapping['id']);
								$entityId = vtws_getId($moduleObjectId, $duplicateRecordId);
								vtws_delete($entityId, $this->user);
							}
							$baseRecordId = $adb->query_result($duplicatesResult, $noOfDuplicates - 1, $fieldColumnMapping['id']);
							$baseEntityId = vtws_getId($moduleObjectId, $baseRecordId);

							if ($mergeType == Import_Utils::$AUTO_MERGE_OVERWRITE) {
								try {
									$fieldData = $this->transformForImport($fieldData, $moduleMeta);
									$fieldData['id'] = $baseEntityId;
									$entityInfo = vtws_update($fieldData, $this->user);
									$entityInfo['status'] = self::$IMPORT_RECORD_UPDATED;
								} catch (Exception $e) {
									$entityInfo['status'] = self::$IMPORT_RECORD_FAILED;
								}
							}

							if ($mergeType == Import_Utils::$AUTO_MERGE_MERGEFIELDS) {
								$filteredFieldData = array();
								$defaultFieldValues = $this->getDefaultFieldValues($moduleMeta);
								foreach ($fieldData as $fieldName => $fieldValue) {
									if (!empty($fieldValue)) {
										$filteredFieldData[$fieldName] = $fieldValue;
									}
								}
								$existingFieldValues = vtws_retrieve($baseEntityId, $this->user);
								foreach ($existingFieldValues as $fieldName => $fieldValue) {
									if (empty($fieldValue)
											&& empty($filteredFieldData[$fieldName])
											&& !empty($defaultFieldValues[$fieldName])) {
										$filteredFieldData[$fieldName] = $fieldValue;
									}
								}
								try {
									$filteredFieldData = $this->transformForImport($filteredFieldData, $moduleMeta, false);
									$filteredFieldData['id'] = $baseEntityId;
									$entityInfo = vtws_revise($filteredFieldData, $this->user);
									$entityInfo['status'] = self::$IMPORT_RECORD_MERGED;
								} catch (Exception $e) {
									$entityInfo['status'] = self::$IMPORT_RECORD_FAILED;
								}
							}
						} else {
							$createRecord = true;
						}
					} else {
						$createRecord = true;
					}
				} else {
					$createRecord = true;
				}
				if ($createRecord) {
					try {
						$fieldData = $this->transformForImport($fieldData, $moduleMeta);
					} catch (Exception $e) {
						$fieldData = null;
					}
					if($fieldData == null) {
						$entityInfo = null;
					} else {
						// crmv@178491
						try {
							$entityInfo = vtws_create($moduleName, $fieldData, $this->user);
							$entityInfo['status'] = self::$IMPORT_RECORD_CREATED;
						} catch(Exception $e) {
							$entityInfo = null;
						}
						// crmv@178491e
					}
				}
			}

			if($entityInfo == null) {
				$entityInfo = array('id' => null, 'status' => self::$IMPORT_RECORD_FAILED);
			}

			$this->importedRecordInfo[$rowId] = $entityInfo;
			$this->updateImportStatus($rowId, $entityInfo);
		}
		unset($result);
		return true;
	}
	// crmv@90216e
	
	public function transformForImport($fieldData, $moduleMeta, $fillDefault=true) {
		$moduleFields = $moduleMeta->getModuleFields();
		$defaultFieldValues = $this->getDefaultFieldValues($moduleMeta);
		$fieldsFormats = $this->fieldsFormats; // crmv@83878

		foreach ($fieldData as $fieldName => $fieldValue) {
			$fieldInstance = $moduleFields[$fieldName];
			
			// crmv@83878
			if (!empty($fieldsFormats[$fieldName]) && $fieldValue != '') {
				$fieldData[$fieldName] = $fieldValue = $this->convertValueFromFormat($fieldValue, $fieldsFormats[$fieldName], $fieldInstance);
			}
			// crmv@83878e
			
			if ($fieldInstance->getFieldDataType() == 'owner') {
				$ownerId = getUserId_Ol($fieldValue);
				if (empty($ownerId)) {
					$ownerId = getGrpId($fieldValue);
				}
				if (empty($ownerId) && isset($defaultFieldValues[$fieldName])) {
					$ownerId = $defaultFieldValues[$fieldName];
				}
				if(empty($ownerId) ||
							!Import_Utils::hasAssignPrivilege($moduleMeta->getEntityName(), $ownerId)) {
					$ownerId = $this->user->id;
				}
				$fieldData[$fieldName] = $ownerId;

			} elseif ($fieldInstance->getFieldDataType() == 'reference') {
				$entityId = false;
				if (!empty($fieldValue)) {
					if(strpos($fieldValue, '::::') > 0) {
						$fieldValueDetails = explode('::::', $fieldValue);
					} else {
						$fieldValueDetails = explode(':::', $fieldValue);
					}
					if (count($fieldValueDetails) > 1) {
						$referenceModuleName = trim($fieldValueDetails[0]);
						$entityLabel = trim($fieldValueDetails[1]);
						$entityId = getEntityId($referenceModuleName, $entityLabel);
					} else {
						$referencedModules = $fieldInstance->getReferenceList();
						$entityLabel = $fieldValue;
						foreach ($referencedModules as $referenceModule) {
							$referenceModuleName = $referenceModule;
							if ($referenceModule == 'Users') {
								$referenceEntityId = getUserId_Ol($entityLabel);
								if(empty($referenceEntityId) ||
										!Import_Utils::hasAssignPrivilege($moduleMeta->getEntityName(), $referenceEntityId)) {
									$referenceEntityId = $this->user->id;
								}
							} else {
								$referenceEntityId = getEntityId($referenceModule, $entityLabel);
							}
							if ($referenceEntityId != 0) {
								$entityId = $referenceEntityId;
								break;
							}
						}
					}
					if ((empty($entityId) || $entityId == 0) && !empty($referenceModuleName)) {
						if(isPermitted($referenceModuleName, 'EditView') == 'yes') {
							$wsEntityIdInfo = $this->createEntityRecord($referenceModuleName, $entityLabel);
							$wsEntityId = $wsEntityIdInfo['id'];
							$entityIdComponents = vtws_getIdComponents($wsEntityId);
							$entityId = $entityIdComponents[1];
						}
					}
					$fieldData[$fieldName] = $entityId;
				} else {
					$referencedModules = $fieldInstance->getReferenceList();
					if ($referencedModules[0] == 'Users') {
						if(isset($defaultFieldValues[$fieldName])) {
							$fieldData[$fieldName] = $defaultFieldValues[$fieldName];
						}
						if(empty($fieldData[$fieldName]) ||
								!Import_Utils::hasAssignPrivilege($moduleMeta->getEntityName(), $fieldData[$fieldName])) {
							$fieldData[$fieldName] = $this->user->id;
						}
					} else {
						$fieldData[$fieldName] = '';
					}
				}

			} elseif ($fieldInstance->getFieldDataType() == 'picklist') {
				global $default_charset;
				if (empty($fieldValue) && isset($defaultFieldValues[$fieldName])) {
					$fieldData[$fieldName] = $fieldValue = $defaultFieldValues[$fieldName];
				}
				$allPicklistDetails = $fieldInstance->getPicklistDetails();
				$allPicklistValues = array();
				foreach ($allPicklistDetails as $picklistDetails) {
					$allPicklistValues[] = strtolower($picklistDetails['value']); //crmv@36235
				}
				//crmv@36771
//				$encodePicklistValue = htmlentities($fieldValue,ENT_QUOTES,$default_charset);
				$encodePicklistValue = trim($fieldValue);
				//crmv@36771e
				if (!in_array(strtolower($encodePicklistValue), $allPicklistValues)) { //crmv@36235
					$moduleObject = Vtecrm_Module::getInstance($moduleMeta->getEntityName());
					$fieldObject = Vtecrm_Field::getInstance($fieldName, $moduleObject);
					$fieldObject->setPicklistValues(array($fieldValue));
				}
			} else {
				if ($fieldInstance->getFieldDataType() == 'datetime' && !empty($fieldValue)) {
					if($fieldValue == null || $fieldValue == '0000-00-00 00:00:00') {
						$fieldValue = '';
					}
					$valuesList = explode(' ', $fieldValue);
					if(count($valuesList) == 1) $fieldValue = '';
					$fieldValue = getValidDBInsertDateTimeValue($fieldValue);
					if (preg_match("/^[0-9]{2,4}[-][0-1]{1,2}?[0-9]{1,2}[-][0-3]{1,2}?[0-9]{1,2} ([0-1][0-9]|[2][0-3])([:][0-5][0-9]){1,2}$/",
							$fieldValue) == 0) {
						$fieldValue = '';
					}
					$fieldData[$fieldName] = $fieldValue;
				}
				if ($fieldInstance->getFieldDataType() == 'date' && !empty($fieldValue)) {
					if($fieldValue == null || $fieldValue == '0000-00-00') {
						$fieldValue = '';
					}
					$fieldValue = getValidDBInsertDateValue($fieldValue);
					if (preg_match("/^[0-9]{2,4}[-][0-1]{1,2}?[0-9]{1,2}[-][0-3]{1,2}?[0-9]{1,2}$/", $fieldValue) == 0) {
						$fieldValue = '';
					}
					// no need of this, the date should always be in DB format!
					//$fieldData[$fieldName] = getDisplayDate($fieldValue);	//crmv@33544 crmv@83878
				}
				if (empty($fieldValue) && isset($defaultFieldValues[$fieldName])) {
					$fieldData[$fieldName] = $fieldValue = $defaultFieldValues[$fieldName];
				}
			}
		}
		if($fillDefault) {
			foreach($defaultFieldValues as $fieldName => $fieldValue) {
				if (!isset($fieldData[$fieldName])) {
					$fieldData[$fieldName] = $defaultFieldValues[$fieldName];
				}
			}
		}

		foreach ($moduleFields as $fieldName => $fieldInstance) {
			if(empty($fieldData[$fieldName]) && $fieldInstance->isMandatory($this->user) && ($this->mergeType != Import_Utils::$AUTO_MERGE_MERGEFIELDS)) {	//crmv@33651	//crmv@49510
				return null;
			}
		}

		return DataTransform::sanitizeData($fieldData, $moduleMeta);
	}
	
	// crmv@83878
	public function convertValueFromFormat($value, $format, $fieldInstance) {
		$uitype = $fieldInstance->getUIType();
		$datatype = $fieldInstance->getFieldDataType();

		if ($uitype == 7 || $uitype == 71 || $uitype == 72) {
			// number or currency
			list($ts,$ds) = explode(':', $format);
			$ts = str_replace(array('EMPTY', 'PERIOD', 'COMMA', 'SPACE', 'QUOTE'), array('', '.', ',', ' ',  "'"), $ts);
			$ds = str_replace(array('EMPTY', 'PERIOD', 'COMMA', 'SPACE', 'QUOTE'), array('', '.', ',', ' ',  "'"), $ds);
			
			$IU = InventoryUtils::getInstance();
			$IU->decimalSeparator = $ds;
			$IU->thousandsSeparator = $ts;
			
			$value = $IU->parseUserNumber($value);
		} elseif ($datatype == 'date') {
			// date
			$date = DateTime::createFromFormat($format, $value);
			if ($date == false) {
				// wrong date/format
				$value = '';
			} else {
				// format it in the DB format
				$value = $date->format('Y-m-d');
			}
		}
		
		return $value;
	}
	// crmv@83878e

	public function createEntityRecord($moduleName, $entityLabel) {
		$moduleHandler = vtws_getModuleHandlerFromName($moduleName, $this->user);
		$moduleMeta = $moduleHandler->getMeta();
		$moduleFields = $moduleMeta->getModuleFields();
		$mandatoryFields = $moduleMeta->getMandatoryFields();
		$entityNameFieldsString = $moduleMeta->getNameFields();
		$entityNameFields = explode(',', $entityNameFieldsString);
		$fieldData = array();
		foreach ($entityNameFields as $entityNameField) {
			$entityNameField = trim($entityNameField);
			if (in_array($entityNameField, $mandatoryFields)) {
				$fieldData[$entityNameField] = $entityLabel;
			}
		}
		foreach ($mandatoryFields as $mandatoryField) {
			if (empty($fieldData[$mandatoryField])) {
				$fieldInstance = $moduleFields[$mandatoryField];
				if ($fieldInstance->getFieldDataType() == 'owner') {
					$fieldData[$mandatoryField] = $this->user->id;
				} else {
					$fieldData[$mandatoryField] = '????';
				}
			}
		}
		$fieldData = DataTransform::sanitizeData($fieldData, $moduleMeta);
		$entityIdInfo = vtws_create($moduleName, $fieldData, $this->user);
		$focus = CRMEntity::getInstance($moduleName);
		$focus->updateMissingSeqNumber($moduleName);
		return $entityIdInfo;
	}

	public function getImportStatusCount() {
		$adb = PearDatabase::getInstance();

		$tableName = Import_Utils::getDbTableName($this->user);
		$result = $adb->query('SELECT status FROM '.$tableName);

		$statusCount = array('TOTAL' => 0, 'IMPORTED' => 0, 'FAILED' => 0, 'PENDING' => 0,
								'CREATED' => 0, 'SKIPPED' => 0, 'UPDATED' => 0, 'MERGED' => 0);

		if($result) {
			$noOfRows = $adb->num_rows($result);
			$statusCount['TOTAL'] = $noOfRows;
			for($i=0; $i<$noOfRows; ++$i) {
				$status = $adb->query_result($result, $i, 'status');
				if(self::$IMPORT_RECORD_NONE == $status) {
					$statusCount['PENDING']++;

				} elseif(self::$IMPORT_RECORD_FAILED == $status) {
					$statusCount['FAILED']++;

				} else {
					$statusCount['IMPORTED']++;
					switch($status) {
						case self::$IMPORT_RECORD_CREATED	:	$statusCount['CREATED']++;
																break;
						case self::$IMPORT_RECORD_SKIPPED	:	$statusCount['SKIPPED']++;
																break;
						case self::$IMPORT_RECORD_UPDATED	:	$statusCount['UPDATED']++;
																break;
						case self::$IMPORT_RECORD_MERGED	:	$statusCount['MERGED']++;
																break;
					}
				}

			}
		}
		return $statusCount;
	}

	public static function runScheduledImport() {
		global $current_user;
		$scheduledImports = self::getScheduledImport();
		foreach ($scheduledImports as $scheduledId => $importDataController) {
			$current_user = $importDataController->user;
			//crmv@181281 removed code, now batchImport is true and use the importBatchLimit
			
			if(!$importDataController->initializeImport()) { continue; }
			//crmv@34704 crmv@138021
			// removed status change, since the locking mechanism is now handled by the unified cron
			//$importInfo = Import_Queue_Controller::getImportInfo($importDataController->module, $importDataController->user);
			//Import_Queue_Controller::updateStatus($importInfo['id'], Import_Queue_Controller::$IMPORT_STATUS_RUNNING);
			//crmv@34704e crmv@138021
			$importDataController->importData();
			
			$importStatusCount = $importDataController->getImportStatusCount();
			//crmv@181281
			if ($importStatusCount['PENDING'] > 0) {
				Import_Lock_Controller::unLock($importDataController->user, $importDataController->module);
			} else {
				//crmv@29617
				$focus = ModNotifications::getInstance(); // crmv@164122
				$focus->saveFastNotification(
					array(
						'assigned_user_id' => $importDataController->user->id,
						'mod_not_type' => 'Import Completed',
						'createdtime' => date('Y-m-d H:i:s'),
						'modifiedtime' => date('Y-m-d H:i:s'),
						'description' => $importDataController->module,
					),false
				);
				//crmv@29617e
				
				$importDataController->finishImport();
			}
			//crmv@181281e
		}
	}

	public static function getScheduledImport() {

		$scheduledImports = array();
		$importQueue = Import_Queue_Controller::getAll(Import_Queue_Controller::$IMPORT_STATUS_SCHEDULED);
		foreach($importQueue as $importId => $importInfo) {
			$userId = $importInfo['user_id'];
			$user = CRMEntity::getInstance('Users');
			$user->id = $userId;
			$user->retrieve_entity_info($userId, 'Users');

			$scheduledImports[$importId] = new Import_Data_Controller($importInfo, $user);
		}
		return $scheduledImports;
	}

}

?>