<?php
/* crmv@190016 */
namespace JiraRestApi\Issue;

class IssueType implements \JsonSerializable
{
    /** @var string */
    public $self;

    /** @var string */
    public $id;

    /** @var string|null */
    public $description;

    /** @var string */
    public $iconUrl;

    /** @var string */
    public $name;

    /** @var bool */
    public $subtask;

    /** @var \JiraRestApi\Issue\IssueStatus[] */
    public $statuses;
	
	 public function setName($name)
    {
        $this->name = $name;
    }

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this));
    }
}
