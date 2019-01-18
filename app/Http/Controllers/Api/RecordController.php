<?php

namespace App\Http\Controllers\Api;

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

    public function store()
    {
        $name = $this->request->name ?? '';
        $data = $this->request->data ?? '{}';
        $password = Crypt::encrypt($this->request->password ?? '');

        $record = $this->record->create(compact([
            'name',
            'data',
            'password',
        ]));

        return response($record, 201);
    }

    public function show()
    {
        $id = Hashids::decode($this->request->code);

        $record = $this->record->find($id);

        try {
            if (Crypt::decrypt($record->password) !== $this->request->password) {
                throw new \Exception('Incorrect code or password.');
            }

            return response([
                'data' => $record,
            ], 200);
        } catch (\Exception $e) {
            return response([
                'error' => $e->getMessage(),
            ], 400);
        } catch (\Throwable $t) {
            return response([
                'error' => $t->getMessage(),
            ], 500);
        }
    }

    public function destory()
    {
        $id = Hashids::decode($this->request->code);

        $record = $this->record->find($id);

        try {
            if (Crypt::decrypt($record->password) !== $this->request->password) {
                throw new \Exception('Incorrect code or password.');
            }

            if ($record->delete()) {
                return response([
                    //
                ], 204);
            }
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
