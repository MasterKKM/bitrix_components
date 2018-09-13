<h1>Для битрикса</h1>
<h2>imp:menu.sections Компанент битрикс, аналог компанента "Пункты меню" только для HighLoad блоков</h2>
<p> Собирает многоуровневое меню. Использовать в файле .***.menu_ext.php</p><p>Пример:<pre>
$aMenuLinks = $APPLICATION->IncludeComponent(
    "imp:menu.sections",
    "",
    Array(
        "HL_BLOCK_ID" => "1", // ID Hi-Load блока.
        "HL_BLOCK_PARENT_NAME" => "UF_PARENT", // Поле связи в котором указан Id родительского пункта меню.
        "HL_BLOCK_SECTION_NAME" => "UF_NAME",  // Поле хранящее текст пункта меню.
        "HL_BLOCK_SECTION_URL" => "particion/#ID#/" // Формирование ссылок на соответсвующие разделы.
    )
);
</pre></p>
<p>Типы данных HighLoad блока:
<pre>
    UF_NAME   - Строка, с наименованием пункта меню.
    UF_PARENT - Привязка элементов Hi-Load блоков. Выбрать поле ID этого же блока.
</pre>
</p>
<p>Кеширование осуществлялось memcache, в обход механизьмов битрикс и сейчас убрано!</p>