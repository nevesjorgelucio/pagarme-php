<?php

namespace PagarMe\Sdk\BalanceOperation\Request;

use PagarMe\Sdk\RequestInterface;

class BalanceOperationList implements RequestInterface
{

    /**
     * @var int
     */
    private $page;

    /**
     * @var int
     */
    private $count;

    /**
     * @var string
     */
    private $status;

    /**
     * @var
     */
    private $start;

    /**
     * @var
     */
    private $end;

    /**
     * @var
     */
    private $recipient;

    /**
     * BalanceOperationList constructor.
     * @param $page
     * @param $count
     * @param $status
     * @param null $recipient
     * @param null $start
     * @param null $end
     */
    public function __construct($page, $count, $status, $recipient = null, $start = null, $end = null)
    {
        $this->page   = $page;
        $this->count  = $count;
        $this->status = $status;
        $this->recipient = $recipient;
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        return [
            'page'   => $this->page,
            'count'  => $this->count,
            'status' => $this->status,
            'recipient_id' => $this->recipient,
            'start_date' => $this->start,
            'end_date' => $this->end
        ];
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return 'balance/operations';
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return self::HTTP_GET;
    }
}
