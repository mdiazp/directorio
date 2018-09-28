<?php

class LdapFilterHandler {
    private $INITIAL_SEARCH_STATE = 'BEGIN';
    private $FINAL_SEARCH_STATE = 'END';
    private $TOTAL_SEARCH_STATES = 6; 

    function __construct(){}

    function GetFilter( $displaynameQuery , $search_state ){
        $filter = '(&';
        $filter .= '(' . $this->GetDisplaynameFilter( $displaynameQuery , $search_state ) . ')';
        
        $filter .= '(displayname=*)';
        //$filter .= '(|(OU=_Usuarios,DC=upr,DC=edu,DC=cu)(OU=_GrupoRedes,DC=upr,DC=edu,DC=cu))';
        
        $filter .= ')';

        return $filter;
    }

    function _INITIAL_SEARCH_STATE(){
        return $this->INITIAL_SEARCH_STATE;
    }

    function _FINAL_SEARCH_STATE(){
        return $this->FINAL_SEARCH_STATE;
    }

    function GetNextSearchState( $search_state ){
        if( $search_state == $this->INITIAL_SEARCH_STATE || $search_state == '' ){
            $search_state = 1;
        }
        if( $search_state == $this->FINAL_SEARCH_STATE ){
            return $search_state;
        }

        $search_state++;
        if( $search_state > $this->TOTAL_SEARCH_STATES ){
            $search_state = $this->FINAL_SEARCH_STATE;
        } 
        return $search_state;
    }

    private function GetDisplaynameFilter( $displaynameQuery , $search_state ){
        $words = $this->SplitDisplaynameQuery( $displaynameQuery );
        if( count($words) == 0 ){
            return 'displayname=------';
        }

        if( $search_state == $this->INITIAL_SEARCH_STATE || $search_state == '' ){
            $search_state = 1;
        }

        if( $search_state == 1 ){
            return $this->GetDisplaynameFilterInState1( $words );
        }
        
        if( $search_state == 2 ){
            return $this->GetDisplaynameFilterInState2( $words );
        }

        if( $search_state == 3 ){
            return $this->GetDisplaynameFilterInState3( $words );
        }

        if( $search_state == 4 ){
            return $this->GetDisplaynameFilterInState4( $words );
        }

        if( $search_state == 5 && count($words) == 1 ){
            return $this->GetFilterInState5( $words );
        }

        if( $search_state == 6 ){
            return $this->GetFilterInState6( $words  );
        }

        return 'displayname=------';
    }

    private function GetDisplaynameFilterInState1( $words ){
        return 'displayname='. $this->GetFilterExpr1( $words );
    } 

    private function GetDisplaynameFilterInState2( $words ){
        
        return '&(displayname='. $this->GetFilterExpr2($words) . ')(!(displayname='. $this->GetFilterExpr1($words) .'))';
    } 

    private function GetDisplaynameFilterInState3( $words ){
        return '&(displayname='. $this->GetFilterExpr3( $words) . ')(!(displayname='. $this->GetFilterExpr1($words) .'))' .
                                                                  '(!(displayname='. $this->GetFilterExpr2($words) .'))';
    } 

    private function GetDisplaynameFilterInState4( $words ){
        return '&(displayname='. $this->GetFilterExpr4( $words ) . ')(!(displayname='. $this->GetFilterExpr1($words) .'))' .
                                                                   '(!(displayname='. $this->GetFilterExpr2($words) .'))' .
                                                                   '(!(displayname='. $this->GetFilterExpr3($words) .'))';
    }

    private function GetFilterInState5( $words ){
        return '&(samaccountname='. $words[0] .'*)'.'(!(displayname='. $this->GetFilterExpr1($words) .'))' .
                                                    '(!(displayname='. $this->GetFilterExpr2($words) .'))' .
                                                    '(!(displayname='. $this->GetFilterExpr3($words) .'))' .
                                                    '(!(displayname='. $this->GetFilterExpr4($words) .'))';
    }

    private function GetFilterInState6( $words ){
        return '&(physicaldeliveryofficename='. $this->GetFilterExpr4( $words ) .')'.
                                                    '(!(displayname='. $this->GetFilterExpr1($words) .'))' .
                                                    '(!(displayname='. $this->GetFilterExpr2($words) .'))' .
                                                    '(!(displayname='. $this->GetFilterExpr3($words) .'))' .
                                                    '(!(displayname='. $this->GetFilterExpr4($words) .'))' .
                                                    '(!(samaccountname='. $words[0] . '))';
    }
    
    private function GetFilterExpr1( $words ){
        $sz = count( $words );
        $str = '';
        for( $i = 0; $i < $sz; $i++ ){
            $str .= $words[$i];
            if( $i+1 < $sz ){
                $str .= ' ';
            }
        }
        return $str;
    }

    private function GetFilterExpr2( $words ){   
        $sz = count( $words );
        $str = '';
        for( $i = 0; $i < $sz; $i++ ){
            $str .= $words[$i];
            if( $i+1 < $sz ){
                $str .= ' ';
            }
            else if( $i > 0 ){
                $str .= '*';
            }
            else{
                $str .= ' *';
            }
        }
        
        return $str;
    }

    private function GetFilterExpr3( $words ){
        $sz = count($words);
        $str = '';
        for( $i = 0; $i < $sz; $i++ ){
            $str .= $words[$i];
            if( $i + 1 < $sz ){
                $str .= '* ';
            }
            else{
                $str .= '*';
            }
        }
        return $str;
    }

    private function GetFilterExpr4( $words ){
        $sz = count($words);
        $str = '*';
        for( $i = 0; $i < $sz; $i++ ){
            $str .= $words[$i] . '*';
        }
        return $str;
    }

    private function SplitDisplaynameQuery( $displaynameQuery ){
        $str = $displaynameQuery;
        $word = '';
        
        $search = array();
        $sz = 0;
        $str_size = strlen($str);

        for ($i = 0, $len = $str_size; $i < $len; $i++) {
            if( $str[$i] != ' ' ){
                $word .= $str[$i];
                if( $i+1 == $str_size ){
                    $search[$sz++] = $word;
                }
            }
            else if( $word != '' ){
                $search[$sz++] = $word;
                $word = '';
            }
        }

        return $search;
    }
}

?>