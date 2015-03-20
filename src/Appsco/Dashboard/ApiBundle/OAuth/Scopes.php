<?php

namespace Appsco\Dashboard\ApiBundle\OAuth;

final class Scopes
{
    const PROFILE_READ = 'profile_read';
    const DASHBOARD_LIST = 'dashboard_list';
    const DASHBOARD_ICON_LIST = 'dashboard_icon_list';
    const DASHBOARD_APPLICATION_ADD = 'dashboard_application_add';

    private function __construct() { }
}