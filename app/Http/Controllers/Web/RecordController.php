<?php

namespace App\Http\Controllers\Web;

use App\Record;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class RecordController extends Controller
{
    protected $request;
    protected $record;

    public function __construct(Request $request, Record $record)
    {
        $this->request = $request;
        $this->record = $record;
    }

    public function show()
    {
        $id = Hashids::decode($this->request->code);

        $record = $this->record->find($id);

        try {
            if (Crypt::decrypt($record->password) !== $this->request->password) {
                throw new \Exception('Incorrect code or password.');
            }

            return response($record->data, 200);
        } catch (\Exception $e){
            return response([
                'error' => $e->getMessage(),
            ], 400);
        } catch (\Throwable $t) {
            return response([
                'error' => $t->getMessage(),
            ], 500);
        }
    }
}
