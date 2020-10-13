<?php

namespace App;

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
    public function scopeSearchSmall($query, $term)
    {
        $columns = implode(',', $this->searchable);

        // $query->whereRaw("MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE)", $this->fullTextWildcards($term));

        $query->with('sigun')->with('nonghyup')
              ->join('siguns', 'small_farmers.sigun_code', 'siguns.code')
              ->join('users', 'small_farmers.nonghyup_id', 'users.nonghyup_id')
              ->select(
                  'small_farmers.*', 'siguns.sequence as sigun_sequence', 'siguns.name as sigun_name',
                  'users.sequence as nonghyup_sequence', 'users.name as nonghyup_name'
              )
              ->where('users.is_admin', '!=', 1);


        if ($term)
            $query->whereRaw("MATCH (small_farmers.{$columns}) AGAINST (? IN BOOLEAN MODE)", $this->fullTextWildcards($term));

        return $query;
    }

    public function scopeSearchLarge($query, $term)
    {
        $columns = implode(',', $this->searchable);

        // $query->whereRaw("MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE)", $this->fullTextWildcards($term));

        $query->with('sigun')->with('nonghyup')
              ->join('siguns', 'large_farmers.sigun_code', 'siguns.code')
              ->join('users', 'large_farmers.nonghyup_id', 'users.nonghyup_id')
              ->select(
                  'large_farmers.*', 'siguns.sequence as sigun_sequence', 'siguns.name as sigun_name',
                  'users.sequence as nonghyup_sequence', 'users.name as nonghyup_name'
                )
              ->where('users.is_admin', '!=', 1);

        if ($term)
            $query->whereRaw("MATCH (large_farmers.{$columns}) AGAINST (? IN BOOLEAN MODE)", $this->fullTextWildcards($term));

        return $query;
    }

    public function scopeSearchMachine($query, $term)
    {
        $columns = implode(',', $this->searchable);

        $query->with('sigun')->with('nonghyup')
              ->join('siguns', 'machine_supporters.sigun_code', 'siguns.code')
              ->join('users', 'machine_supporters.nonghyup_id', 'users.nonghyup_id')
              ->select(
                  'machine_supporters.*', 'siguns.sequence as sigun_sequence', 'siguns.name as sigun_name',
                  'users.sequence as nonghyup_sequence', 'users.name as nonghyup_name'
                )
              ->where('users.is_admin', '!=', 1);

        if ($term)
            $query->whereRaw("MATCH (machine_supporters.{$columns}) AGAINST (? IN BOOLEAN MODE)", $this->fullTextWildcards($term));

        return $query;
    }

    public function scopeSearchManpower($query, $term)
    {
        $columns = implode(',', $this->searchable);

        $query->with('sigun')->with('nonghyup')
              ->join('siguns', 'manpower_supporters.sigun_code', 'siguns.code')
              ->join('users', 'manpower_supporters.nonghyup_id', 'users.nonghyup_id')
              ->select(
                  'manpower_supporters.*', 'siguns.sequence as sigun_sequence', 'siguns.name as sigun_name',
                  'users.sequence as nonghyup_sequence', 'users.name as nonghyup_name'
                )
              ->where('users.is_admin', '!=', 1);

        if ($term)
            $query->whereRaw("MATCH (manpower_supporters.{$columns}) AGAINST (? IN BOOLEAN MODE)", $this->fullTextWildcards($term));

        return $query;
    }
}
