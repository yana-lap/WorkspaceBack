<?php

namespace Legacy\Api;
use Legacy\Helper;
use Bitrix\Main\UserTable;

class User {
    public static function GetUser() {
        global $USER;

        if ($USER->IsAuthorized()) {
            return Helper::GetResponseApi(200, [
                'user' => self::GetUserInfo()
            ]);
        }
        else{
            return Helper::GetResponseApi(200, []);
        }
    }

    public static function GetUserInfo() {
        global $USER;

        $result = [];

        $user = UserTable::getRow([
            'select' => [
                'ID',
                'LOGIN',
                'NAME',
                'LAST_NAME',
                'EMAIL'
            ],
            'filter' => ['ID' => $USER->GetID()]
        ]);

        if ($user) {
            $userGroupID = implode(array_diff($USER->GetUserGroup($USER->GetID()), ["2"]));

            $userGroup = \CGroup::GetByID($userGroupID)->Fetch();

            $userGroupInfo = ['name' => $userGroup['NAME'], 'string_id' => $userGroup['STRING_ID']];

            $result = [
                "id" => $user['ID'],
                "login" => $user['LOGIN'],
                "email" => $user['EMAIL'],
                "name" => $user['NAME'],
                "last_name" => $user['LAST_NAME'],
                "group" => $userGroupInfo
            ];
        }

        return $result;
    }

    public static function UpdateUserData($arRequest) {
        global $USER;

        $userID = $arRequest['user_id'];
        $login = $arRequest['login'];
        $name = $arRequest['name'];
        $lastname = $arRequest['last_name'];
        $email = $arRequest['email'];

        $arFields = Array(
            "LOGIN" => $login,
            "NAME" => $name,
            "LAST_NAME" => $lastname,
            "EMAIL" => $email,
        );

        if($USER->Update($userID, $arFields)){
            return Helper::GetResponseApi(200, [
                'user' => self::GetUserInfo()
            ]);
        }
        else{
            return Helper::GetResponseApi(400, [], $USER->LAST_ERROR
            );
        }

    }

    public static function UpdateUserPassword($arRequest)
    {
        global $USER;

        $userID = $arRequest['user_id'];
        $password = $arRequest['password'];
        $confirm_password = $arRequest['confirm_password'];

        $arFields = array(
            "PASSWORD" => $password,
            "CONFIRM_PASSWORD" => $confirm_password,
        );

        if($USER->Update($userID, $arFields)){
            return Helper::GetResponseApi(200, [
                'user' => self::GetUserInfo()
            ]);
        }
        else{
            return Helper::GetResponseApi(400, [], $USER->LAST_ERROR
            );
        }

    }
}