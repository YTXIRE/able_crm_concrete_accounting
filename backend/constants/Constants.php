<?php

class Constants
{
    public static string $POST_METHOD_NOT_ALLOWED = 'Пожалуйста, используйте метод POST для этого запроса';
    public static string $PUT_METHOD_NOT_ALLOWED = 'Пожалуйста, используйте метод PUT для этого запроса';
    public static string $DELETE_METHOD_NOT_ALLOWED = 'Пожалуйста, используйте метод DELETE для этого запроса';
    public static string $OK = 'OK';
    public static string $GET_METHOD_NOT_ALLOWED = 'Пожалуйста, используйте метод GET для этого запроса';
    public static string $PLEASE_MAKE_SURE_THAT_ALL_THE_REQUIRED_FIELDS_ARE_FILLED_IN_CORRECTLY = 'Пожалуйста, убедитесь, что все необходимые поля заполнены правильно';
    public static string $MAXIMUM_LOGIN_LENGTH = 'Максимальная длина логина может быть 100 символов';
    public static string $MAXIMUM_PASSWORD_LENGTH = 'Максимальная длина пароля может быть 100 символов';
    public static string $MAXIMUM_TOKEN_LENGTH = 'Максимальная длина токена может быть 100 символов';
    public static string $SPECIFY_FILTERS = 'Пожалуйста, укажите фильтр';
    public static string $USER_NOT_FOUND = 'Такого пользователя не существует';
    public static string $INCORRECT_LOGIN_OR_PASSWORD = 'Неверный логин или пароль';
    public static string $INTERNAL_SERVER_ERROR = 'Внутренняя ошибка сервера';
    public static string $PLEASE_SPECIFY_USER_TOKEN = 'Пожалуйста, укажите токен пользователя';
    public static string $SUCCESS_REQUEST = 'Успешный запрос';
    public static string $USER_SUCCESSFULLY_LOGOUT = 'Пользователь успешно вышел из системы';
    public static string $USER_WITH_TOKEN_NOT_FOUND = 'Пользователь с указанным токеном не найден';
    public static string $ID_MUST_BE_INTEGER = 'Идентификатор должен быть целым числом и должен быть больше нуля';
    public static string $USER_WITH_ID_NOT_FOUND = 'Пользователь с указанным идентификатором не найден';
    public static string $SPECIFY_FILE = 'Укажите файл';
    public static string $FILE_UNRESOLVED_EXTENSION_IMAGE = 'Файл имеет не допустимое расширение. Должно быть: png, jpeg или jpg';
    public static string $FILE_SIZE_MUST_LESS_THAN_500_PIXELS = 'Размер файла должен быть меньше 500 пикселей по ширине и высоте';
    public static string $FILE_WEIGHT_MUST_BE_LESS_THAN_500_KILOBYTES = 'Вес файла должен быть менее 500 килобайт';
    public static string $FILE_WEIGHT_MUST_BE_LESS_THAN_30_MB = 'Вес файла должен быть менее 5 мегабайт';
    public static string $LIMIT_MUST_BE_GREATER_THAN_ZERO = 'Лимит должен быть больше нуля';
    public static string $OFFSET_MUST_BE_GREATER_THAN_ZERO = 'Смещение должно быть больше нуля';
    public static string $NOT_ENOUGH_RIGHTS_CONTACT_YOUR_ADMINISTRATOR = 'Недостаточно прав. Обратитесь к своему администратору';
    public static string $PLEASE_SPECIFY_NAME_OF_THE_TYPE_OF_MATERIAL = 'Пожалуйста, укажите название типа материала';
    public static string $PLEASE_SPECIFY_NAME_OF_THE_OF_MATERIAL = 'Пожалуйста, укажите название материала';
    public static string $MAXIMUM_LENGTH_OF_THE_NAME_OF_THE_TYPE_OF_MATERIAL_CAN_BE_100_CHARACTERS = 'Максимальная длина названия типа материала может быть 100 символов';
    public static string $TYPE_ORGANIZATION_WITH_THIS_ID_WAS_NOT_FOUND = 'Тип организации с указанным идентификатором не найден';
    public static string $NEW_LEGAL_ENTITY_HAS_BEEN_SUCCESSFULLY_CREATED = 'Новое юридическое лицо успешно создано';
    public static string $ORGANIZATION_WITH_THIS_ID_WAS_NOT_FOUND = 'Организация с указанным идентификатором не найдена';
    public static string $PLEASE_SPECIFY_NAME_LEGAL_ENTITY = 'Пожалуйста, укажите наименование юридического лица';
    public static string $MAXIMUM_LENGTH_MATERIAL_NAME_CAN_BE_100_CHARACTERS = 'Максимальная длина названия материала может быть 100 символов';
    public static string $TYPE_MATERIAL_WITH_NAME_ALREADY_EXISTS = 'Тип материала с указанным названием уже существует';
    public static string $NEW_MATERIAL_SUCCESSFULLY_CREATED = 'Новый материал успешно создан';
    public static string $MAXIMUM_LENGTH_NAME_100_CHARACTERS = 'Максимальная длина названия может быть 100 символов';
    public static string $TYPE_MATERIAL_WITH_ID_NOT_FOUND = 'Тип материала с указанным идентификатором не найден';
    public static string $MATERIAL_WITH_NAME_ALREADY_EXISTS = 'Материал с указанным названием уже существует';
    public static string $MAXIMUM_LENGTH_NAME_LEGAL_ENTITY_100_CHARACTERS = 'Максимальная длина названия юридического лица может быть 20 символов';
    public static string $NEW_TYPE_MATERIAL_SUCCESSFULLY_CREATED = 'Новый тип материала успешно создан';
    public static string $PLEASE_SPECIFY_NAME_OBJECT = 'Пожалуйста, укажите название объекта';
    public static string $NO_SUPPLIER_WITH_ID_FOUND = 'Поставщик с указанным идентификатором не найден';
    public static string $NEW_OBJECT_SUCCESSFULLY_CREATED = 'Новый объект успешно создан';
    public static string $NO_OBJECT_WITH_ID_WAS_FOUND = 'Объект с указанным идентификатором не найден';
    public static string $OBJECT_WAS_REMOVED = 'Объект с указанным идентификатором уже был удален';
    public static string $VENDOR_WAS_REMOVED = 'Поставщик с указанным идентификатором уже был удален';
    public static string $LEGAL_ENTITY_WAS_REMOVED = 'Юридическое лицо с указанным идентификатором уже было удалено';
    public static string $OBJECT_WAS_UPDATED_ERROR = 'Обновлять можно только активные объекты';
    public static string $VENDOR_WAS_UPDATED_ERROR = 'Обновлять можно только активных поставщиков';
    public static string $LEGAL_ENTITY_WAS_UPDATED_ERROR = 'Обновлять можно только активные юридические лица';
    public static string $OBJECT_WAS_RESTORED = 'Объект с указанным идентификатором уже был восстановлен';
    public static string $VENDOR_WAS_RESTORED = 'Поставщик с указанным идентификатором уже был восстановлен';
    public static string $LEGAL_ENTITY_WAS_RESTORED = 'Юридическое лицо с указанным идентификатором уже было восстановлено';
    public static string $THIS_OBJECT_NAME_IN_VENDOR_EXISTS = 'Объект с указанным названием уже есть';
    public static string $SUM_MUST_INTEGER_AND_MUST_GREATER_ZERO = 'Сумма должна быть числом и должна быть больше нуля';
    public static string $AMOUNT_MUST_LESS_THAN_1000000000RUBLES = 'Сумма должна быть меньше 1 000 000 000 рублей';
    public static string $LEGAL_ENTITY_WITH_ID_NOT_FOUND = 'Юридическое лицо с указанным идентификатором не найдено';
    public static string $NEW_PAYMENT_CREATED_SUCCESSFULLY = 'Новый платеж успешно создан';
    public static string $NO_PAYMENT_WITH_ID_WAS_FOUND = 'Платежа с указанным идентификатором не найдено';
    public static string $MAXIMUM_LENGTH_TIME_ZONE_100_CHARACTERS = 'Максимальная длина часового пояса может быть 100 символов';
    public static string $PLEASE_SPECIFY_TIME_ZONE = 'Пожалуйста, укажите часовой пояс';
    public static string $NO_SUCH_TIME_ZONE_WAS_FOUND = 'Указанного часового пояса не найдено';
    public static string $NO_TIME_ZONE_WITH_THIS_ID_WAS_FOUND = 'Часового пояса с указанным идентификатором не найдено';
    public static string $TIME_ZONE_HAS_BEEN_UPDATED_SUCCESSFULLY = 'Часовой пояс успешно обновлен';
    public static string $PLEASE_SPECIFY_NAME_SUPPLIER = 'Пожалуйста, укажите название поставщика';
    public static string $PLEASE_SPECIFY_NAME_FILTER = 'Пожалуйста, укажите название фильтра';
    public static string $MAXIMUM_LENGTH_NAME_30_CHARACTERS = 'Максимальная длина названия может быть 30 символов';
    public static string $ICONS_WITH_ID_WERE_NOT_FOUND = 'Иконки с указанным идентификатором не найдено';
    public static string $NEW_SUPPLIER_SUCCESSFULLY_CREATED = 'Новый поставщик успешно создан';
    public static string $NEW_FILTER_SUCCESSFULLY_CREATED = 'Новый фильтр успешно создан';
    public static string $MAXIMUM_LENGTH_EMAIL_100_CHARACTERS = 'Максимальная длина электронной почты может быть 100 символов';
    public static string $INVALID_EMAIL_ADDRESS = 'Неверный адрес электронной почты. Пожалуйста, проверьте правильность адреса электронной почты';
    public static string $PASSWORD_MUST_CONTAIN_LEAST_5_CHARACTERS = 'Пароль должен содержать не менее 5 символов';
    public static string $EMAIL_ADDRESS_OR_LOGIN_ALREADY_OCCUPIED = 'Указанным адрес электронной почты или логин уже занят';
    public static string $PASSWORD_SUCCESSFULLY_CHANGED = 'Пароль был успешно изменен';
    public static string $YOU_CANNOT_REMOVE_ADMINISTRATOR = 'Вы не можете удалить администратора';
    public static string $YOU_CANNOT_CHANGE_PASSWORD_ADMINISTRATOR = 'Вы не можете изменить пароль администратора';
    public static string $USER_SUCCESSFULLY_DELETED = 'Пользователь был успешно удален';
    public static string $LEGAL_ENTITY_SUCCESSFULLY_REMOVED = 'Юридическое лицо успешно удалено';
    public static string $LEGAL_ENTITY_SUCCESSFULLY_RESTORED = 'Юридическое лицо успешно восстановлено';
    public static string $LEGAL_ENTITY_ARCHIVE_OR_ACTIVE = 'Юридические лица могут быть либо активные - 0, либо архивные 1';
    public static string $OBJECT_SUCCESSFULLY_REMOVED = 'Объект успешно удален';
    public static string $OBJECT_SUCCESSFULLY_RESTORED = 'Объект успешно восстановлен';
    public static string $OBJECT_ARCHIVE_OR_ACTIVE = 'Объекты могут быть либо активные - 0, либо архивные 1';
    public static string $VENDOR_SUCCESSFULLY_REMOVED = 'Поставщик успешно удален';
    public static string $FILTER_SUCCESSFULLY_REMOVED = 'Фильтр успешно удален';
    public static string $FILTER_SUCCESSFULLY_UPDATED = 'Фильтр успешно обновлен';
    public static string $VENDOR_SUCCESSFULLY_RESTORED = 'Поставщик успешно восстановлен';
    public static string $VENDOR_ARCHIVE_OR_ACTIVE = 'Поставщики могут быть либо активные - 0, либо архивные 1';
    public static string $DEBT_ON_OR_OFF = 'Итог на конец года может быть либо активным - 1, либо выключенным 0';
    public static string $CONFIRMATED_DATA_IS_ACTIVE_OR_NO_ACTIVE = 'Подтверждение данных может быть либо подтверждено - 1, либо не подтверждено 0';
    public static string $PAYMENT_SUCCESSFULLY_REMOVED = 'Платеж успешно удален';
    public static string $USER_WITH_TOKEN_AND_ID_NOT_FOUND = 'Пользователь с указанным токеном и идентификатором не найден';
    public static string $UNITS_VOLUME_NOT_FOUND = 'Величина объема с указанным идентификатором не найдена';
    public static string $PLEASE_SPECIFY_COMMENT = 'Пожалуйста, укажите комментарий';
    public static string $MAXIMUM_LENGTH_COMMENT_CAN_BE_1000_CHARACTERS = 'Максимальная длина комментария может быть 1000 символов';
    public static string $MATERIAL_WITH_ID_NOT_FOUND = 'Материал с указанным идентификатором не найден';
    public static string $VOLUME_MUST_INTEGER_AND_MUST_GREATER_ZERO = 'Объем должен быть числом и должен быть больше нуля';
    public static string $VOLUME_MUST_LESS_THAN_1000000000 = 'Объем должен быть меньше 1 000 000 000';
    public static string $FILE_UNRESOLVED_EXTENSION = 'Файл имеет не допустимое расширение';
    public static string $ERROR_UPLOAD_FILE = 'Ошибка загрузки файла. Попробуйте еще раз';
    public static string $SUCCESS_OPERATION_CREATED = 'Новая операция успешно добавлена';
    public static string $SUCCESS_OPERATION_UPDATED = 'Операция успешно обновлена';
    public static string $OPERATION_WITH_ID_NOT_FOUND = 'Операции с указанным идентификатором не найдено';
    public static string $OPERATION_SUCCESS_REMOVED = 'Операция успешно удалена';
    public static string $DEBT_IS_OFF = 'Итог на конец года уже отключен';
    public static string $DEBT_IS_ON = 'Итог на конец года уже включен';
    public static string $DEBT_IS_ON_RESULT = 'Итог на конец года включен';
    public static string $DEBT_IS_OFF_RESULT = 'Итог на конец года выключен';
    public static string $FILTER_FIELDS_ARE_MISSING = 'Отсутствуют поля фильтра';
    public static string $INVALID_FILTER_TYPE = 'Неверный тип фильтра';
    public static string $INVALID_OPERATION_TYPE = 'Неверный тип операции';
    public static string $DATE_MUST_BE_INTEGER = 'Дата должна быть числом и в формате timestamp';
    public static string $FILTER_NOT_INCLUDES_MATERIAL_AND_MATERIAL_TYPES_IN_PARALLEL = 'В составленном фильтре не могут быть одновременно материал и тип материала';
}