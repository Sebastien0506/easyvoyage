<?php 
namespace App\Service;

use ParagonIE\Halite\KeyFactory;
use ParagonIE\HiddenString\HiddenString;
use ParagonIE\Halite\Symmetric\EncryptionKey;
use ParagonIE\Halite\Symmetric\Crypto as Symmetric;

class EncryptionService
{
    private function EncryptionKey()
    {
        $encryptionKeyString = $_ENV['DONNEE_USER'];
        // dd($encryptionKeyString);

        $encryptionKey = KeyFactory::importEncryptionKey(new HiddenString($encryptionKeyString));
        // dd($encryptionKey);

        return $encryptionKey;
    }
    public function EncryptionNom($nom): string
    {
        $encryptionKey = $this->EncryptionKey();
// dd($encryptionKey);
        $nomCrypter = new HiddenString($nom);
        // dd($nomCrypter);
        $nomCrypter = Symmetric::encrypt($nomCrypter, $encryptionKey);
        // dd($nomCrypter);

        return $nomCrypter;
    }

    public function EncryptionPrenom($prenom): string
    {
       $encryptionKey = $this->EncryptionKey();

        $prenomCrypter = new HiddenString($prenom);

        $prenomCrypter = Symmetric::encrypt($prenomCrypter, $encryptionKey);

        return $prenomCrypter;
    }
}
?>