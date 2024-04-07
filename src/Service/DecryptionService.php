<?php
namespace App\Service;

use ParagonIE\Halite\KeyFactory;
use ParagonIE\Halite\Symmetric\Crypto as Symmetric;
use ParagonIE\HiddenString\HiddenString;

class DecryptionService {
    
    private function DecryptionKey()
    {
        $decryptionKeyObject = $_ENV['DONNEE_USER'];

        $decryptionKey = KeyFactory::importEncryptionKey(new HiddenString($decryptionKeyObject));

        return $decryptionKey;
    }

    public function DecryptionUser($user)
    {
        try{
            $decryptionKey = $this->DecryptionKey();

            $decyptedData = [];

            $nomChiffre = $user->getNom();
            $prenomChiffre = $user->getPrenom();

            $nom = Symmetric::decrypt($nomChiffre, $decryptionKey)->getString();
            $prenom = Symmetric::decrypt($prenomChiffre, $decryptionKey)->getString();

            $decyptedData = [
                'nom' => $nom,
                'prenom' => $prenom,
            ];
        } catch(\Exception $e){
            return ['error' => 'Impossible de décrypter les données.']; 
        }

        return $decyptedData;
    }
}
?>