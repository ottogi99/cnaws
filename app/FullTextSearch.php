<?php

namespace App;
use Illuminate\Support\Facades\Log;


trait FullTextSearch
{
    protected function fullTextWildcards($term)
    {
        // removeing symbols used by MysqlndUhConnection
        $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~'];
        $term = str_replace($reservedSymbols, '', $term);

        $words = explode(' ', $term);

        foreach ($words as $key => $word) {
            /**
            * applying + operator (required word) only big words
            * because smaller ones are not indexed by mysql
            */
            if (strlen($word) >= 3) {
                $words[$key] = '+' . $word . '*';
                // $words[$key] = $word . '*';
            }
        }

        $searchTerm = implode(' ', $words);

        return $searchTerm;
    }

    /**
    * Scope a query that matches a full text search of term.
    *
    * @param \Illuminate\Database\Eloquent\Builder $query
    * @param string $term
    * @return \Illuminate\Database\Eloquent\Builder
    */
    public function scopeSearchSmall($query, $term, $year = null, $nonghyup_id = null)
    {
        $columns = implode(',', $this->searchable);

        // $query->join('users', 'small_farmers.nonghyup_id', 'users.nonghyup_id')
        //       ->select(
        //           'small_farmers.*', 'users.sequence as nonghyup_sequence', 'users.name as nonghyup_name'
        //       );

        if ($term) {
            $query->whereRaw("MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE)", $this->fullTextWildcards($term));
        }
        $query->whereRaw('business_year = ?', [$year]);
        $query->whereRaw('nonghyup_id = ?', [$nonghyup_id]);

        return $query;
    }

    public function scopeSearchLarge($query, $term, $year = null, $nonghyup_id = null)
    {
        $columns = implode(',', $this->searchable);

        // $query->join('users', 'large_farmers.nonghyup_id', 'users.nonghyup_id')
        //       ->select(
        //           'large_farmers.*', 'users.sequence as nonghyup_sequence', 'users.name as nonghyup_name'
        //         );

        if ($term) {
            $query->whereRaw("MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE)", $this->fullTextWildcards($term));
        }
        $query->whereRaw('business_year = ?', [$year]);
        $query->whereRaw('nonghyup_id = ?', [$nonghyup_id]);

        return $query;
    }

    public function scopeSearchMachine($query, $term, $year = null, $nonghyup_id = null)
    {
        $columns = implode(',', $this->searchable);

        // $query->join('users', 'machine_supporters.nonghyup_id', 'users.nonghyup_id')
        //       ->select(
        //           'machine_supporters.*', 'users.sequence as nonghyup_sequence', 'users.name as nonghyup_name'
        //         );

        if ($term) {
            $query->whereRaw("MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE)", $this->fullTextWildcards($term));
        }
        $query->whereRaw('business_year = ?', [$year]);
        $query->whereRaw('nonghyup_id = ?', [$nonghyup_id]);

        // $query->whereRaw("(users.is_admin != 1 AND machine_supporters.business_year = 2020 AND machine_supporters.nonghyup_id = 'nh457095')", [1, $year, $nonghyup_id])
        // ->orderbyRaw("siguns.sequence")
        // ->orderbyRaw("users.sequence")
        // ->orderbyRaw("machine_supporters.name");

        return $query;
    }

    public function scopeSearchManpower($query, $term)
    {
        $columns = implode(',', $this->searchable);

        // $query->join('users', 'manpower_supporters.nonghyup_id', 'users.nonghyup_id')
        //       ->select(
        //           'manpower_supporters.*', 'users.sequence as nonghyup_sequence', 'users.name as nonghyup_name'
        //         );

        if ($term) {
            $query->whereRaw("MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE)", $this->fullTextWildcards($term));
        }
        $query->whereRaw('business_year = ?', [$year]);
        $query->whereRaw('nonghyup_id = ?', [$nonghyup_id]);

        return $query;
    }
}
