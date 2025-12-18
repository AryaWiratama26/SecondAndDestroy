<?php

namespace App\Http\Controllers;

use App\Services\CryptoTesting\EncryptionTestService;
use App\Services\CryptoTesting\AvalancheEffectService;

class CryptoTestController extends Controller
{
    public function avalancheTest()
    {
        $encryptService = new EncryptionTestService();
        $avalancheService = new AvalancheEffectService();

        $iv = str_repeat("\x00", 16);

        $tests = [
            ['p1' => 'Nama=Ainun;HP=081234567890', 'p2' => 'Nama=Binun;HP=081234567891'],
            ['p1' => 'Nama=Arya;HP=082345678902',  'p2' => 'Nama=Orya;HP=082345678903'],
            ['p1' => 'Nama=farel;HP=085612345674',  'p2' => 'Nama=Parel;HP=085612345675'],
            ['p1' => 'Nama=Indah;HP=089612345675',  'p2' => 'Nama=Endah;HP=083612345676'],
            ['p1' => 'Nama=Agus;HP=087612345677',  'p2' => 'Parel=Ugus;HP=085612345678'],
        ];

        $results = [];

        foreach ($tests as $i => $test) {
            $c1 = $encryptService->encryptForAvalanche($test['p1'], $iv);
            $c2 = $encryptService->encryptForAvalanche($test['p2'], $iv);

            $results[] = [
                'pengujian' => 'Uji ' . ($i + 1),
                'avalanche (%)' => round(
                    $avalancheService->calculate($c1, $c2), 2
                )
            ];
        }

        return response()->json($results);
    }
}
