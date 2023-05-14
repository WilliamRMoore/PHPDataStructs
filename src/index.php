<?php
ini_set('memory_limit', '-1');


require_once './vendor/autoload.php';

use DSL\SkipList;

// mt_srand(25);

// $list2 = SkipList::Create();
// $list2->SetMaxSize(500000)->SetMaxLevels(25);
$list2 = [];
$s = hrtime(true);

for ($i = 200000; $i >= 1; $i--) {
    //$list2->Add($i, $i);
    $list2[$i] = $i;
}
sort($list2);
$e = hrtime(true);

newLine();
echo ($e - $s) / 1000000000;
newLine();
newLine();

foreach ($list2 as $key => $value) {
    echo "$key, $value";
    newLine();
}

function newLine()
{
    echo "<br>";
    echo "<br>";
}
