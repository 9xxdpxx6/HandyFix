<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = [
            ['code' => 'AF', 'name' => 'Афганистан'],
            ['code' => 'AL', 'name' => 'Албания'],
            ['code' => 'DZ', 'name' => 'Алжир'],
            ['code' => 'AS', 'name' => 'Американское Самоа'],
            ['code' => 'AD', 'name' => 'Андорра'],
            ['code' => 'AO', 'name' => 'Ангола'],
            ['code' => 'AI', 'name' => 'Ангилья'],
            ['code' => 'AQ', 'name' => 'Антарктида'],
            ['code' => 'AR', 'name' => 'Аргентина'],
            ['code' => 'AM', 'name' => 'Армения'],
            ['code' => 'AW', 'name' => 'Аруба'],
            ['code' => 'AU', 'name' => 'Австралия'],
            ['code' => 'AT', 'name' => 'Австрия'],
            ['code' => 'AZ', 'name' => 'Азербайджан'],
            ['code' => 'BS', 'name' => 'Багамы'],
            ['code' => 'BH', 'name' => 'Бахрейн'],
            ['code' => 'BD', 'name' => 'Бангладеш'],
            ['code' => 'BB', 'name' => 'Барбадос'],
            ['code' => 'BY', 'name' => 'Беларусь'],
            ['code' => 'BE', 'name' => 'Бельгия'],
            ['code' => 'BZ', 'name' => 'Белиз'],
            ['code' => 'BJ', 'name' => 'Бенин'],
            ['code' => 'BM', 'name' => 'Бермудские Острова'],
            ['code' => 'BT', 'name' => 'Бутан'],
            ['code' => 'BO', 'name' => 'Боливия'],
            ['code' => 'BA', 'name' => 'Босния и Герцеговина'],
            ['code' => 'BW', 'name' => 'Ботсвана'],
            ['code' => 'BR', 'name' => 'Бразилия'],
            ['code' => 'BN', 'name' => 'Бруней'],
            ['code' => 'BG', 'name' => 'Болгария'],
            ['code' => 'BF', 'name' => 'Буркина-Фасо'],
            ['code' => 'BI', 'name' => 'Бурунди'],
            ['code' => 'KH', 'name' => 'Камбоджа'],
            ['code' => 'CM', 'name' => 'Камерун'],
            ['code' => 'CA', 'name' => 'Канада'],
            ['code' => 'CV', 'name' => 'Кабо-Верде'],
            ['code' => 'KY', 'name' => 'Каймановы Острова'],
            ['code' => 'CF', 'name' => 'ЦАР'],
            ['code' => 'TD', 'name' => 'Чад'],
            ['code' => 'CL', 'name' => 'Чили'],
            ['code' => 'CN', 'name' => 'Китай'],
            ['code' => 'CO', 'name' => 'Колумбия'],
            ['code' => 'KM', 'name' => 'Коморы'],
            ['code' => 'CG', 'name' => 'Конго'],
            ['code' => 'CD', 'name' => 'Демократическая Республика Конго'],
            ['code' => 'CR', 'name' => 'Коста-Рика'],
            ['code' => 'HR', 'name' => 'Хорватия'],
            ['code' => 'CU', 'name' => 'Куба'],
            ['code' => 'CY', 'name' => 'Кипр'],
            ['code' => 'CZ', 'name' => 'Чехия'],
            ['code' => 'DK', 'name' => 'Дания'],
            ['code' => 'DJ', 'name' => 'Джибути'],
            ['code' => 'DM', 'name' => 'Доминика'],
            ['code' => 'DO', 'name' => 'Доминиканская Республика'],
            ['code' => 'EC', 'name' => 'Эквадор'],
            ['code' => 'EG', 'name' => 'Египет'],
            ['code' => 'SV', 'name' => 'Сальвадор'],
            ['code' => 'GQ', 'name' => 'Экваториальная Гвинея'],
            ['code' => 'ER', 'name' => 'Эритрея'],
            ['code' => 'EE', 'name' => 'Эстония'],
            ['code' => 'SZ', 'name' => 'Эсватини'],
            ['code' => 'ET', 'name' => 'Эфиопия'],
            ['code' => 'FJ', 'name' => 'Фиджи'],
            ['code' => 'FI', 'name' => 'Финляндия'],
            ['code' => 'FR', 'name' => 'Франция'],
            ['code' => 'GA', 'name' => 'Габон'],
            ['code' => 'GM', 'name' => 'Гамбия'],
            ['code' => 'GE', 'name' => 'Грузия'],
            ['code' => 'DE', 'name' => 'Германия'],
            ['code' => 'GH', 'name' => 'Гана'],
            ['code' => 'GI', 'name' => 'Гибралтар'],
            ['code' => 'GR', 'name' => 'Греция'],
            ['code' => 'GL', 'name' => 'Гренландия'],
            ['code' => 'GD', 'name' => 'Гренада'],
            ['code' => 'GU', 'name' => 'Гуам'],
            ['code' => 'GT', 'name' => 'Гватемала'],
            ['code' => 'GN', 'name' => 'Гвинея'],
            ['code' => 'GW', 'name' => 'Гвинея-Бисау'],
            ['code' => 'GY', 'name' => 'Гайана'],
            ['code' => 'HT', 'name' => 'Гаити'],
            ['code' => 'HN', 'name' => 'Гондурас'],
            ['code' => 'HK', 'name' => 'Гонконг'],
            ['code' => 'HU', 'name' => 'Венгрия'],
            ['code' => 'IS', 'name' => 'Исландия'],
            ['code' => 'IN', 'name' => 'Индия'],
            ['code' => 'ID', 'name' => 'Индонезия'],
            ['code' => 'IR', 'name' => 'Иран'],
            ['code' => 'IQ', 'name' => 'Ирак'],
            ['code' => 'IE', 'name' => 'Ирландия'],
            ['code' => 'IM', 'name' => 'Остров Мэн'],
            ['code' => 'IL', 'name' => 'Израиль'],
            ['code' => 'IT', 'name' => 'Италия'],
            ['code' => 'CI', 'name' => 'Кот д’Ивуар'],
            ['code' => 'JM', 'name' => 'Ямайка'],
            ['code' => 'JP', 'name' => 'Япония'],
            ['code' => 'JO', 'name' => 'Иордания'],
            ['code' => 'KZ', 'name' => 'Казахстан'],
            ['code' => 'KE', 'name' => 'Кения'],
            ['code' => 'KI', 'name' => 'Кирибати'],
            ['code' => 'KP', 'name' => 'Северная Корея'],
            ['code' => 'KR', 'name' => 'Южная Корея'],
            ['code' => 'KW', 'name' => 'Кувейт'],
            ['code' => 'KG', 'name' => 'Кыргызстан'],
            ['code' => 'LA', 'name' => 'Лаос'],
            ['code' => 'LV', 'name' => 'Латвия'],
            ['code' => 'LB', 'name' => 'Ливан'],
            ['code' => 'LS', 'name' => 'Лесото'],
            ['code' => 'LR', 'name' => 'Либерия'],
            ['code' => 'LY', 'name' => 'Ливия'],
            ['code' => 'LI', 'name' => 'Лихтенштейн'],
            ['code' => 'LT', 'name' => 'Литва'],
            ['code' => 'LU', 'name' => 'Люксембург'],
            ['code' => 'MO', 'name' => 'Макао'],
            ['code' => 'MK', 'name' => 'Северная Македония'],
            ['code' => 'MG', 'name' => 'Мадагаскар'],
            ['code' => 'MW', 'name' => 'Малави'],
            ['code' => 'MY', 'name' => 'Малайзия'],
            ['code' => 'MV', 'name' => 'Мальдивы'],
            ['code' => 'ML', 'name' => 'Мали'],
            ['code' => 'MT', 'name' => 'Мальта'],
            ['code' => 'MH', 'name' => 'Маршалловы Острова'],
            ['code' => 'MQ', 'name' => 'Мартиника'],
            ['code' => 'MR', 'name' => 'Мавритания'],
            ['code' => 'MU', 'name' => 'Маврикий'],
            ['code' => 'YT', 'name' => 'Майотта'],
            ['code' => 'MX', 'name' => 'Мексика'],
            ['code' => 'FM', 'name' => 'Федеративные Штаты Микронезии'],
            ['code' => 'MD', 'name' => 'Молдова'],
            ['code' => 'MC', 'name' => 'Монако'],
            ['code' => 'MN', 'name' => 'Монголия'],
            ['code' => 'ME', 'name' => 'Черногория'],
            ['code' => 'MS', 'name' => 'Монтсеррат'],
            ['code' => 'MA', 'name' => 'Марокко'],
            ['code' => 'MZ', 'name' => 'Мозамбик'],
            ['code' => 'MM', 'name' => 'Мьянма'],
            ['code' => 'NA', 'name' => 'Намибия'],
            ['code' => 'NR', 'name' => 'Науру'],
            ['code' => 'NP', 'name' => 'Непал'],
            ['code' => 'NL', 'name' => 'Нидерланды'],
            ['code' => 'NC', 'name' => 'Новая Каледония'],
            ['code' => 'NZ', 'name' => 'Новая Зеландия'],
            ['code' => 'NI', 'name' => 'Никарагуа'],
            ['code' => 'NE', 'name' => 'Нигер'],
            ['code' => 'NG', 'name' => 'Нигерия'],
            ['code' => 'NU', 'name' => 'Ниуэ'],
            ['code' => 'NF', 'name' => 'Остров Норфолк'],
            ['code' => 'MP', 'name' => 'Северные Марианские Острова'],
            ['code' => 'NO', 'name' => 'Норвегия'],
            ['code' => 'OM', 'name' => 'Оман'],
            ['code' => 'PK', 'name' => 'Пакистан'],
            ['code' => 'PW', 'name' => 'Палау'],
            ['code' => 'PS', 'name' => 'Палестинская территория'],
            ['code' => 'PA', 'name' => 'Панама'],
            ['code' => 'PG', 'name' => 'Папуа — Новая Гвинея'],
            ['code' => 'PY', 'name' => 'Парагвай'],
            ['code' => 'PE', 'name' => 'Перу'],
            ['code' => 'PH', 'name' => 'Филиппины'],
            ['code' => 'PN', 'name' => 'Питкерн'],
            ['code' => 'PL', 'name' => 'Польша'],
            ['code' => 'PT', 'name' => 'Португалия'],
            ['code' => 'PR', 'name' => 'Пуэрто-Рико'],
            ['code' => 'QA', 'name' => 'Катар'],
            ['code' => 'RE', 'name' => 'Реюньон'],
            ['code' => 'RO', 'name' => 'Румыния'],
            ['code' => 'RU', 'name' => 'Россия'],
            ['code' => 'RW', 'name' => 'Руанда'],
            ['code' => 'BL', 'name' => 'Сен-Бартельми'],
            ['code' => 'SH', 'name' => 'Сент-Елена'],
            ['code' => 'KN', 'name' => 'Сент-Китс и Невис'],
            ['code' => 'LC', 'name' => 'Сент-Люсия'],
            ['code' => 'MF', 'name' => 'Сен-Мартен'],
            ['code' => 'PM', 'name' => 'Сен-Пьер и Микелон'],
            ['code' => 'VC', 'name' => 'Сент-Винсент и Гренадины'],
            ['code' => 'WS', 'name' => 'Самоа'],
            ['code' => 'SM', 'name' => 'Сан-Марино'],
            ['code' => 'ST', 'name' => 'Сан-Томе и Принсипи'],
            ['code' => 'SA', 'name' => 'Саудовская Аравия'],
            ['code' => 'SN', 'name' => 'Сенегал'],
            ['code' => 'RS', 'name' => 'Сербия'],
            ['code' => 'SC', 'name' => 'Сейшельские Острова'],
            ['code' => 'SL', 'name' => 'Сьерра-Леоне'],
            ['code' => 'SG', 'name' => 'Сингапур'],
            ['code' => 'SX', 'name' => 'Синт-Мартен'],
            ['code' => 'SK', 'name' => 'Словакия'],
            ['code' => 'SI', 'name' => 'Словения'],
            ['code' => 'SB', 'name' => 'Соломоновы Острова'],
            ['code' => 'SO', 'name' => 'Сомали'],
            ['code' => 'ZA', 'name' => 'Южноафриканская Республика'],
            ['code' => 'GS', 'name' => 'Южная Георгия и Южные Сандвичевы Острова'],
            ['code' => 'SS', 'name' => 'Южный Судан'],
            ['code' => 'ES', 'name' => 'Испания'],
            ['code' => 'LK', 'name' => 'Шри-Ланка'],
            ['code' => 'SD', 'name' => 'Судан'],
            ['code' => 'SR', 'name' => 'Суринам'],
            ['code' => 'SJ', 'name' => 'Шпицберген и Ян-Майен'],
            ['code' => 'SE', 'name' => 'Швеция'],
            ['code' => 'CH', 'name' => 'Швейцария'],
            ['code' => 'SY', 'name' => 'Сирия'],
            ['code' => 'TW', 'name' => 'Тайвань'],
            ['code' => 'TJ', 'name' => 'Таджикистан'],
            ['code' => 'TZ', 'name' => 'Танзания'],
            ['code' => 'TH', 'name' => 'Таиланд'],
            ['code' => 'TL', 'name' => 'Восточный Тимор'],
            ['code' => 'TG', 'name' => 'Того'],
            ['code' => 'TK', 'name' => 'Токелау'],
            ['code' => 'TO', 'name' => 'Тонга'],
            ['code' => 'TT', 'name' => 'Тринидад и Тобаго'],
            ['code' => 'TN', 'name' => 'Тунис'],
            ['code' => 'TR', 'name' => 'Турция'],
            ['code' => 'TM', 'name' => 'Туркменистан'],
            ['code' => 'TC', 'name' => 'Острова Теркс и Кайкос'],
            ['code' => 'TV', 'name' => 'Тувалу'],
            ['code' => 'UG', 'name' => 'Уганда'],
            ['code' => 'UA', 'name' => 'Украина'],
            ['code' => 'AE', 'name' => 'Объединённые Арабские Эмираты'],
            ['code' => 'GB', 'name' => 'Великобритания'],
            ['code' => 'US', 'name' => 'США'],
            ['code' => 'UM', 'name' => 'Внешние малые острова США'],
            ['code' => 'UY', 'name' => 'Уругвай'],
            ['code' => 'UZ', 'name' => 'Узбекистан'],
            ['code' => 'VU', 'name' => 'Вануату'],
            ['code' => 'VA', 'name' => 'Ватикан'],
            ['code' => 'VE', 'name' => 'Венесуэла'],
            ['code' => 'VN', 'name' => 'Вьетнам'],
            ['code' => 'WF', 'name' => 'Уоллис и Футуна'],
            ['code' => 'EH', 'name' => 'Западная Сахара'],
            ['code' => 'YE', 'name' => 'Йемен'],
            ['code' => 'ZM', 'name' => 'Замбия'],
            ['code' => 'ZW', 'name' => 'Зимбабве']
        ];

        DB::table('countries')->insertOrIgnore($countries);
    }
}
