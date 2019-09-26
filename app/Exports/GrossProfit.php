<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;

use App\Invoice;

class GrossProfit implements FromQuery
{

	/**
	*
	* Create a New  Gross Profit Export
	* @param $from_date string From Date in Y-m-d format
	* @param $to_date string To Date in Y-m-d format
	*
	*/

	public function __construct($from_date, $to_date) {
		$this->from_date 	= $from_date;
		$this->to_date 		= $to_date;
	}

    /**
    * @return \Illuminate\Database\Query\Builder
    */
    public function query()
    {
        return Invoice::join('sales_orders', 'sales_orders.id', '=', 'invoices.so_id')
        				->whereBetween('invoice_date', [$this->from_date, $this->to_date])
        				->when(isset($this->customer_id), function($q){
        						return $q->where('invoices.customer_id', $this->customer_id);
        				});
    }

    /**
    *
	* Add Customer Filter to the report
	* @param $customer_id int|array Customer IDs to filter out in the export
	* @return \App\Exports\GrossProfit
	*
	*/
    public function forCustomer($customer_id)
    {
    	$this->customer_id = $customer_id;
    	return $this;
    }

    /**
    *
	* Add State Filter to the report
	* @param $state_id int|array State IDs to filter out in the export
	* @return \App\Exports\GrossProfit
	*
	*/
    public function forState($state_id)
    {
    	$this->state_id = $state_id;
    	return $this;
    }
}
