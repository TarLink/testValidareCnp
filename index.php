<?php
/******************************************************************************/
/****                            Validare CNP                              ****/
/******************************************************************************/
/**
 * Validate CNP ( valid for 1800-2099 )
 *
 * @param string $value
 * @return boolean
 */
function isCnpValid(string $value): bool {
    // CNP must have 13 characters
    if(strlen($value) != 13) {
        return false;
    }
    $cnp = str_split($value);
    unset($value);
    $hashTable = array( 2 , 7 , 9 , 1 , 4 , 6 , 3 , 5 , 8 , 2 , 7 , 9 );
    $hashResult = 0;
    // All characters must be numeric
    for($i=0 ; $i<13 ; $i++) {
        if(!is_numeric($cnp[$i])) {
            return false;
        }
        $cnp[$i] = (int)$cnp[$i];
        if($i < 12) {
            $hashResult += (int)$cnp[$i] * (int)$hashTable[$i];
        }
    }
    unset($hashTable, $i);
    $hashResult = $hashResult % 11;
    if($hashResult == 10) {
        $hashResult = 1;
    }
    // Check Year
    $year = ($cnp[1] * 10) + $cnp[2];
    switch( $cnp[0] ) {
        case 1  : case 2 : { $year += 1900; } break; // cetateni romani nascuti intre 1 ian 1900 si 31 dec 1999
        case 3  : case 4 : { $year += 1800; } break; // cetateni romani nascuti intre 1 ian 1800 si 31 dec 1899
        case 5  : case 6 : { $year += 2000; } break; // cetateni romani nascuti intre 1 ian 2000 si 31 dec 2099
        case 7  : case 8 : case 9 : {                // rezidenti si Cetateni Straini
            $year += 2000;
            if($year > (int)date('Y')-14) {
                $year -= 100;
            }
        } break;
        default : {
            return false;
        } break;
    }
    //validate date
    $month = ($cnp[3]*10 + $cnp[4]);
    $day = ($cnp[5]*10 + $cnp[6]);
    if(!checkdate($month,$day,$year)){
        return false;
    }
    //validate JJ
    $jj = ($cnp[7]*10 + $cnp[8]);
    if($jj > 52 || $jj <= 0){
        return false;
    }
    return true;
}
?>
