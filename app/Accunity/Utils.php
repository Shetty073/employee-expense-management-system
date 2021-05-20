<?php

namespace App\Accunity;

class Utils
{
    public const PAYMENT_MODES = array('Cash', 'Cheque', 'Bank Transfer', 'GPay');
    public const VOUCHER_STATUS = array('Approval Not Requested', 'Waiting For Approval', 'Approved', 'Declined');
    public const VOUCHER_STATUS_CSS = array('badge badge-secondary', 'badge badge-primary', 'badge badge-success', 'badge badge-danger');

}
