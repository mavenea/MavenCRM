<?php
/*************************************
 * SPDX-FileCopyrightText: 2009-2020 Vtenext S.r.l. <info@vtenext.com> 
 * SPDX-License-Identifier: AGPL-3.0-only  
 ************************************/
/* crmv@55961 */
$focusNewsletter = CRMEntity::getInstance('Newsletter');
$email = $focus->column_fields[$focusNewsletter->email_fields[$currentModule]['fieldname']];
$newsletter_unsub_status = $focusNewsletter->receivingNewsletter($email);
$smarty->assign('RECEIVINGNEWSLETTER',$newsletter_unsub_status);
?>