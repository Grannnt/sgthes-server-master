<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $page_title = "";

    public function response_message($data = [])
    {
        $info = collect($data);
        $info = $info->except(['success', 'message', 'description']);

        $ret = [
            'success'       => $data['success'] ?? false,
            'message'       => $this->page_title ?? ($data['success'] == true ? 'Success' : 'Error'),
            'description'   => $data['description'] ?? 'Something went wrong'
        ];

        $ret += $info->all();

        return $ret;
    }

    public function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    public function generate_number($limit, $id = "")
    {
        return substr(number_format(time() * rand(), 0, '', ''), 0, $limit) . ($id ?  "-" . $id : "");
    }
}
