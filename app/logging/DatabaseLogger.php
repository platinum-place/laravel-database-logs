<?php

namespace App\Logging;

use App\Models\Log;
use Illuminate\Database\Eloquent\Model;
use Monolog\Handler\AbstractProcessingHandler;

class DatabaseLogger extends AbstractProcessingHandler
{
    protected function write(array|\Monolog\LogRecord $record): void
    {
        $data = [];
        $context = $record['context'];

        foreach ($record['context'] as $key => $item) {
            if ($item instanceof Model) {
                $data['loggable_id'] = $item->id;
                $data['loggable_type'] = get_class($item);
                unset($context[$key]);
                break;
            }
        }

        $data['message'] = $record['message'];
        $data['level_name'] = $record['level_name'];
        $data['context'] = $context;

        Log::create($data);
    }
}
