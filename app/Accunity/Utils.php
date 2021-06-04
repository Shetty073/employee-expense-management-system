<?php

namespace App\Accunity;

class Utils
{
    public const PAYMENT_MODES = array('Cash', 'Cheque', 'Bank Transfer', 'GPay');
    public const PAYMENT_MODES_ICONS = array('money-bill-alt', 'money-check-alt', 'piggy-bank', 'google-pay');
    public const VOUCHER_STATUS = array('Approval Not Requested', 'Waiting For Approval', 'Approved', 'Rejected');
    public const VOUCHER_STATUS_CSS = array('badge badge-secondary', 'badge badge-primary', 'badge badge-success', 'badge badge-danger');

    // Email constants
    public const SENDER_EMAIL = 'litmusmarine@mailtrap.io';
    public const SENDER_NAME = 'Admin - Litmus Marine';
}
