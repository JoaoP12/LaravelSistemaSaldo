<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\User;

class Balance extends Model
{
    public $timestamps = false;

    public function deposit(float $value) : Array
    {
        DB::beginTransaction();

        $totalBefore = $this->amount ? $this->amount : 0;
        $this->amount += number_format($value, 2, '.', '');
        $deposit = $this->save();

        $history = auth()->user()->history  ()->create([
            'type' => 'I',
            'amount' => $value,
            'total_before' => $totalBefore,
            'total_after' => $this->amount,
            'date' => date('Ymd')
        ]);

        if($deposit && $history){
            DB::commit();
            return [
                'success' => true,
                'message' => 'Sucesso ao recarregar'
            ];
        }else{
            DB::rollback();

            return [
                'success' => false,
                'message' => 'Falha ao recarregar'
            ];
        }
    }

    public function withdraw(float $value) : Array
    {
        if($value > $this->amount)
            return [
                'success' => false,
                'message' => 'Saldo insuficiente'
            ];

        DB::beginTransaction();

        $totalBefore = $this->amount ? $this->amount : 0;
        $this->amount -= number_format($value, 2, '.', '');
        $withdraw = $this->save();

        $history = auth()->user()->history  ()->create([
            'type' => 'O',
            'amount' => $value,
            'total_before' => $totalBefore,
            'total_after' => $this->amount,
            'date' => date('Ymd')
        ]);

        if($withdraw && $history){
            DB::commit();
            return [
                'success' => true,
                'message' => 'Sucesso ao retirar'
            ];
        }else{
            DB::rollback();

            return [
                'success' => false,
                'message' => 'Falha ao retirar'
            ];
           
        }
    }

    public function transfer(float $value, User $sender) : Array
    {
        if($value > $this->amount)
            return [
                'success' => false,
                'message' => 'Saldo insuficiente'
            ];

        DB::beginTransaction();

        /* Atualiza o Próprio saldo */
        $totalBefore = $this->amount ? $this->amount : 0;
        $this->amount -= number_format($value, 2, '.', '');
        $transfer = $this->save();

        $history = auth()->user()->history()->create([
            'type' => 'T',
            'amount' => $value,
            'total_before' => $totalBefore,
            'total_after' => $this->amount,
            'date' => date('Ymd'),
            'user_id_transaction' => $sender->id
        ]);

        if($transfer && $history){
            DB::commit();

        }else{
            DB::rollback();
        }

        /* Atualiza o Saldo do recebedor */
        $senderBalance = $sender->balance()->firstOrCreate([]);
        $senderTotalBefore = $senderBalance->amount ? $senderBalance->amount : 0;
        $senderBalance->amount += number_format($value, 2, '.', '');
        $transferSender = $senderBalance->save();

        $senderHistory = $sender->history()->create([
            'type' => 'I',
            'amount' => $value,
            'total_before' => $senderTotalBefore,
            'total_after' => $senderBalance->amount,
            'date' => date('Ymd'),
            'user_id_transaction' => auth()->user()->id
        ]);

        if($transferSender && $senderHistory){
            DB::commit();
            return [
                'success' => true,
                'message' => 'Sucesso na transferência'
            ];
        }else{
            DB::rollback();

            return [
                'success' => false,
                'message' => 'Falha na transferência'
            ];
           
        }
    }
}