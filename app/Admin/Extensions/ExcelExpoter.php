<?php

namespace App\Admin\Extensions;

use Carbon\Carbon;
use Encore\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Facades\Excel;

class ExcelExpoter extends AbstractExporter
{
    public function export()
    {

        Excel::create('Filename', function($excel) {

            $excel->sheet('列表', function($sheet) {

                $rows = collect($this->getData())->map(function ($item) {
                    foreach($item as $key=>$i){
                        if($i instanceof Carbon){
                            $item[$key]=$i->toDateTimeString();
                        }
                        if(is_array($i)){
                            $arr=array_pluck($i,'title');
                            $item[$key]=implode(',',$arr);
                        }
                    }
                    return $item;
                });

                $sheet->rows($rows);

            });

        })->export('xls');
    }
}