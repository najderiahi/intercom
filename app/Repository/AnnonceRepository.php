<?php
/**
 * Created by IntelliJ IDEA.
 * User: emmanuel
 * Date: 18/09/19
 * Time: 11:25
 */

namespace App\Repository;


use App\Annonce;

class AnnonceRepository
{
    public function delete(Annonce $annonce) {
        $annonce->delete();
    }
}
