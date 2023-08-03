<?php

namespace Cbonds;

use DOMDocument;

class TaskTwo extends SessionService
{
    const OUTPUT_FILE_NAME = 'emissions.xml';

    # здесь по-хорошему нужно разбить функцию на 2-3, но я слабо знаком с DOMDocument и решил оставить как есть

    /**
     * @throws \DOMException
     */
    public static function createXMLTable(): void
    {
        # получение данных со страницы
        $htmlPage = self::getRequestedPage();
        # генерация документа с нужными параметрами
        $xmlDoc = new DOMDocument();
        $xmlDoc->formatOutput = true;
        $xmlDoc->encoding = 'UTF-8';
        $items = $xmlDoc->createElement('items');
        $xmlDoc->appendChild($items);
        # подготовка данных
        $titlesArray = [];
        $dataArray = [];
        preg_match_all("%<tr((?s).*?)</tr>%", $htmlPage, $dataArray);
        $dataArray = $dataArray[0];
        # вырезаем заголовки таблицы
        $titleDoc = new DOMDocument();
        $titleDoc->loadHTML($dataArray[0]);
        $tableElements = $titleDoc->getElementsByTagName('th');
        foreach ($tableElements as $node) {
            $titlesArray[] = utf8_decode($node->nodeValue);
        }
        array_shift($dataArray);
        # заполняем массив данных
        foreach ($dataArray as $row) {
            $loadDocument = new DOMDocument();
            $loadDocument->loadHTML($row);
            $tableElements = $loadDocument->getElementsByTagName('td');
            $item = $xmlDoc->createElement('item');
            $items->appendChild($item);
            foreach ($tableElements as $index => $node) {
                $curNode = $loadDocument->createElement('property');
                $value= trim($node->nodeValue);
                if (!empty($value)) {
                    $curNode->appendChild($loadDocument->createCDATASection(utf8_decode(trim($node->nodeValue))));
                }
                $newNode = $xmlDoc->importNode($curNode, true);
                $newNode->setAttribute('title', $titlesArray[$index]);
                $item->appendChild($newNode);
            }
        }
        $xmlDoc->save(__DIR__. '/../output/' . self::OUTPUT_FILE_NAME);
    }
}