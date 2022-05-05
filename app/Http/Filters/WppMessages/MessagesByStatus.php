<?php

namespace App\Http\Filters\WppMessages;

use  marcusvbda\vstack\Filter;

class MessagesByStatus extends Filter
{

    public $component   = "select-filter";
    public $label       = "Status";
    public $index = "status";
    public $multiple = true;
    public $_options = ['Aguardando', 'Processando', 'Enviado', 'Enviando', 'Erro'];
    public $ids_values = [
        "waiting", "processing", "sending", "sent", "error"
    ];

    public function __construct()
    {
        foreach ($this->_options as $key => $value) {
            $this->options[] = ["value" => $key + 1, "label" => $value];
        }
        parent::__construct();
    }

    public function apply($query, $value)
    {
        $ids = explode(",", $value);
        $values = array_map(function ($id) {
            return data_get($this->ids_values, intval($id) - 1);
        }, $ids);
        return $query->whereIn("status", $values);
    }
}
