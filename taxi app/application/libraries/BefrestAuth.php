<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class BefrestAuth
{
    private static $uid = 10492;
    private static $apiKey = '08E0F073704C2F57ABB8AB509DECF093';
    private static $sharedKey = 'please-change-this-default-shared-key';

    public static function generateSubscriptionAuth($chid, $sdkVersion = 2) {
        return self::generateAuth(sprintf('/xapi/1/subscribe/%d/%s/%d', self::$uid, $chid, $sdkVersion));
    }

    public static function generatePublishAuth($chid) {
        return self::generateAuth(sprintf('/xapi/1/publish/%d/%s', self::$uid, $chid));
    }

    public static function generateChannelStatusAuth($chid) {
        return self::generateAuth(sprintf('/xapi/1/channel-status/%d/%s', self::$uid, $chid));
    }

    private static function generateAuth($addr = '') {
        $payload = self::base64Encode(hex2bin(md5(sprintf('%s,%s', self::$apiKey, $addr))));

        return self::base64Encode(
            hex2bin(md5(sprintf('%s,%s', self::$sharedKey, $payload))));
    }

    private static function base64Encode($input) {
        $payload = str_replace('+', '-', base64_encode($input));
        $payload = str_replace('=', '', $payload);
        return str_replace('/', '_', $payload);
    }
}
