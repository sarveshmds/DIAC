<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Calculate_fees
{
    protected $fixed_administrative_cost = 30000;
    protected $fixed_emergency_adminis_cost = 500000;

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function new_reference_caluclate_fees($claim_amount, $arbitral_tribunal_strength = 1, $type_of_arbitration, $case_type)
    {
        $fees = false;

        if ($type_of_arbitration == 'DOMESTIC') {
            $fees = $this->caluclate_fees($claim_amount, $arbitral_tribunal_strength);

            if ($case_type == 'GENERAL') {
                $final_fees = $this->general_fees_calculation($fees);
            }

            if ($case_type == 'EMERGENCY') {
                $final_fees = $this->emergency_fees_calculation($fees);
            }

            if ($case_type == 'FASTTRACK') {
                $final_fees = $this->emergency_fees_calculation($fees);
            }

            return $final_fees;
        }

        if ($type_of_arbitration == 'INTERNATIONAL') {
            $fees = $this->caluclate_fees_international($claim_amount, $arbitral_tribunal_strength);

            $fees['arb_your_share_fees_payable_dollar'] = round($fees['each_party_share_in_arb_fees_dollar'] * 0.25);

            $fees['arb_your_share_fees_payable_rupee'] = dollar_to_rupee($fees['arb_your_share_fees_payable_dollar']);

            $fees['your_payable_share_dollar'] = $fees['arb_your_share_fees_payable_dollar'] + $fees['administrative_charges_dollar'];

            $fees['your_payable_share_rupee'] = dollar_to_rupee($fees['your_payable_share_dollar']);

            return $fees;
        }

        if ($fees == false) {
            return false;
        }
    }

    public function general_fees_calculation($fees)
    {

        $fees['total_arbitrator_fees'] = money_round_off($fees['total_arbitrator_fees']);
        $fees['each_party_share_in_arb_fees'] = money_round_off($fees['each_party_share_in_arb_fees']);
        $fees['administrative_charges'] = money_round_off($fees['administrative_charges']);
        $fees['each_party_payable'] = money_round_off($fees['each_party_payable']);

        $fees['arb_your_share_fees_payable'] = money_round_off($fees['each_party_share_in_arb_fees'] * 0.25);
        $fees['your_payable_share'] = money_round_off($fees['arb_your_share_fees_payable'] + $fees['administrative_charges']);

        return $fees;
    }

    public function emergency_fees_calculation($fees)
    {
        $fees['total_arbitrator_fees'] = money_round_off($fees['total_arbitrator_fees']);

        $fees['administrative_charges'] = $this->fixed_emergency_adminis_cost;
        $fees['each_party_payable'] = money_round_off($fees['each_party_share_in_arb_fees'] + $this->fixed_emergency_adminis_cost);

        $fees['each_party_share_in_arb_fees'] = money_round_off($fees['each_party_share_in_arb_fees']);

        $fees['arb_your_share_fees_payable'] = money_round_off($fees['each_party_share_in_arb_fees'] * 0.15);
        $fees['your_payable_share'] = money_round_off($fees['arb_your_share_fees_payable'] + $fees['administrative_charges']);

        return $fees;
    }

    public function case_caluclate_fees($claim_amount, $arbitral_tribunal_strength = 1, $type_of_arbitration)
    {

        $fees = false;

        if ($type_of_arbitration == 'DOMESTIC') {
            $fees = $this->caluclate_fees($claim_amount, $arbitral_tribunal_strength);

            return $fees;
        }

        if ($type_of_arbitration == 'INTERNATIONAL') {
            $fees = $this->caluclate_fees_international($claim_amount, $arbitral_tribunal_strength);

            return $fees;
        }

        if ($fees == false) {
            return false;
        }

        return $fees;
    }

    public function case_caluclate_fees_seperate_assessed($claim_amount, $counter_claim_amount, $arbitral_tribunal_strength = 1, $type_of_arbitration)
    {

        $claim_fees = false;
        $counter_fees = false;

        if ($type_of_arbitration == 'DOMESTIC') {
            $claim_fees = $this->caluclate_fees($claim_amount, $arbitral_tribunal_strength);
            $counter_fees = $this->caluclate_fees($counter_claim_amount, $arbitral_tribunal_strength);

            $fees = [
                'claim_fees' => $claim_fees,
                'counter_fees' => $counter_fees,
            ];

            return $fees;
        }

        if ($type_of_arbitration == 'INTERNATIONAL') {
            $claim_fees = $this->caluclate_fees_international($claim_amount, $arbitral_tribunal_strength);
            $counter_fees = $this->caluclate_fees_international($counter_claim_amount, $arbitral_tribunal_strength);

            $fees = [
                'claim_fees' => $claim_fees,
                'counter_fees' => $counter_fees,
            ];

            return $fees;
        }

        if ($claim_fees == false) {
            return false;
        }

        if ($counter_fees == false) {
            return false;
        }

        // if ($type_of_arbitration == 'DOMESTIC') {
        //     return [
        //         'total_arbitrator_fees' => $fees['total_arbitrator_fees'] + $counter_fees['total_arbitrator_fees'],
        //         'claimant_share_in_arb_fees' => $fees['total_arbitrator_fees'],
        //         'respondant_share_in_arb_fees' => $counter_fees['total_arbitrator_fees'],
        //         'claimant_administrative_charges' => $fees['administrative_charges'],
        //         'respondant_administrative_charges' => $counter_fees['administrative_charges'],
        //         'claimant_each_party_payable' => $fees['total_arbitrator_fees'] + $fees['administrative_charges'],
        //         'respondant_each_party_payable' => $counter_fees['total_arbitrator_fees'] + $counter_fees['administrative_charges'],
        //     ];
        // }

        // if ($type_of_arbitration == 'INTERNATIONAL') {
        //     return [
        //         'total_arbitrator_fees_dollar' => $fees['total_arbitrator_fees_dollar'] + $counter_fees['total_arbitrator_fees_dollar'],
        //         'total_arbitrator_fees_rupee' => $fees['total_arbitrator_fees_rupee'] + $counter_fees['total_arbitrator_fees_rupee'],

        //         'int_total_adm_charges_dollar' => $fees['administrative_charges_dollar'] + $counter_fees['administrative_charges_dollar'],
        //         'int_total_adm_charges_rupee' => $fees['administrative_charges_rupee'] + $counter_fees['administrative_charges_rupee'],

        //         'claimant_share_in_arb_fees_dollar' => $fees['total_arbitrator_fees_dollar'],
        //         'claimant_share_in_arb_fees_rupee' => $fees['total_arbitrator_fees_rupee'],

        //         'claimant_share_in_administrative_charges_dollar' => $fees['administrative_charges_dollar'],
        //         'claimant_share_in_administrative_charges_rupee' => $fees['administrative_charges_rupee'],

        //         'respondant_share_in_arb_fees_dollar' => $counter_fees['total_arbitrator_fees_dollar'],
        //         'respondant_share_in_arb_fees_rupee' => $counter_fees['total_arbitrator_fees_rupee'],

        //         'respondant_share_in_administrative_charges_dollar' => $counter_fees['administrative_charges_dollar'],
        //         'respondant_share_in_administrative_charges_rupee' => $counter_fees['administrative_charges_rupee'],
        //     ];
        // }
    }

    public function caluclate_fees($claim_amount, $arbitral_tribunal_strength = 1)
    {
        if ($claim_amount < 1) {
            return false;
        }

        if ($claim_amount < 500000) {
            $fees_result = $this->calc_upto_5_lakhs($claim_amount, $arbitral_tribunal_strength);
        }

        if ($claim_amount >= 500000 && $claim_amount <= 2000000) {
            $fees_result = $this->calc_5_lakhs_to_20_lakhs($claim_amount, $arbitral_tribunal_strength);
        }
        if ($claim_amount > 2000000 && $claim_amount <= 10000000) {
            $fees_result = $this->calc_20_lakhs_to_1_crore($claim_amount, $arbitral_tribunal_strength);
        }
        if ($claim_amount > 10000000 && $claim_amount <= 100000000) {
            $fees_result = $this->calc_1_crore_to_10_crore($claim_amount, $arbitral_tribunal_strength);
        }
        if ($claim_amount > 100000000 && $claim_amount <= 200000000) {
            $fees_result = $this->calc_10_crore_to_20_crore($claim_amount, $arbitral_tribunal_strength);
        }
        if ($claim_amount > 200000000) {
            $fees_result = $this->calc_above_20_crore($claim_amount, $arbitral_tribunal_strength);
        }

        return $fees_result;
    }

    public function calc_upto_5_lakhs($claim_amount, $arbitral_tribunal_strength)
    {
        $total_dispute = $claim_amount;
        $basic_amount = 500000; // 5 lakh
        $excess_amount = $total_dispute - $basic_amount;


        $basic_arbitrator_fees = 45000; // Rs. 45000

        // Total (Basic + excess)
        // $total_with_basic_plus_excess = $basic_arbitrator_fees+$aaf_excess_amount_percent;

        // Additional Arbitrator’s Fees of 25% of the total fee
        $aaf_total_fee_percent = 0;
        $aaf_total_fee_percentage = 25;
        if ($arbitral_tribunal_strength == 1) {
            $aaf_total_fee_percent = ($basic_arbitrator_fees * $aaf_total_fee_percentage) / 100;
        }
        // Total Arbitrator’s Fees
        $total_arbitrator_fees = $basic_arbitrator_fees * $arbitral_tribunal_strength + $aaf_total_fee_percent;

        // Each party share 
        $each_party_share = $total_arbitrator_fees / 2;

        // Miscellaneous Expenses
        $miscellaneous_expenses = 10000;

        // Each party payable 
        $each_party_payable = $each_party_share + $miscellaneous_expenses;

        return [
            'total_arbitrator_fees' => $total_arbitrator_fees,
            'each_party_share_in_arb_fees' => $each_party_share,
            'administrative_charges' => $miscellaneous_expenses,
            'each_party_payable' => $each_party_payable,
            'basic_amount' => $basic_amount,
            'excess_amount' => $excess_amount,
            'basic_arbitrator_fees' => $basic_arbitrator_fees,
            'additional_arb_fees_details' => [
                'aaf_total_fee_percent' => $aaf_total_fee_percent
            ]
        ];
    }

    public function calc_5_lakhs_to_20_lakhs($claim_amount, $arbitral_tribunal_strength)
    {
        $total_dispute = $claim_amount;
        $basic_amount = 500000; // 5 lakh
        $excess_amount = $total_dispute - $basic_amount;

        $basic_arbitrator_fees = 45000; // Rs. 45000


        // Additional Arbitrator’s Fees @3.5% of Excess Amount
        $excess_amount_percentage = 3.5;
        $aaf_excess_amount_percent = ($excess_amount * $excess_amount_percentage) / 100;



        // Additional Arbitrator’s Fees of 25% of the total fee
        $aaf_total_fee_percent = 0;
        $aaf_total_fee_percentage = 25;
        // => If arbitral tribunal strength is 1 then calculate 25% else do not calculate 25%
        if ($arbitral_tribunal_strength == 1) {
            $aaf_total_fee_percent = (($basic_arbitrator_fees + $aaf_excess_amount_percent) * $aaf_total_fee_percentage) / 100;
        }

        // Total (Basic + excess)
        $total_with_basic_plus_excess = $basic_arbitrator_fees + $aaf_excess_amount_percent;

        // Total Arbitrator’s Fees
        $total_arbitrator_fees = ($basic_arbitrator_fees + $aaf_excess_amount_percent + $aaf_total_fee_percent) * $arbitral_tribunal_strength;

        // Each party share 
        $each_party_share = $total_arbitrator_fees / 2;

        // Miscellaneous Expenses
        $miscellaneous_expenses = 10000;
        // if(total_dispute > 1000000){
        //     miscellaneous_expenses = 10000; // Rs. 10000
        // }
        // else{
        //     miscellaneous_expenses = 5000; // Rs. 5000
        // }

        // Each party payable 
        $each_party_payable = $each_party_share + $miscellaneous_expenses;

        return [
            'total_arbitrator_fees' => $total_arbitrator_fees,
            'each_party_share_in_arb_fees' => $each_party_share,
            'administrative_charges' => $miscellaneous_expenses,
            'each_party_payable' => $each_party_payable,
            'basic_amount' => $basic_amount,
            'excess_amount' => $excess_amount,
            'total_with_basic_plus_excess' => $total_with_basic_plus_excess,
            'basic_arbitrator_fees' => $basic_arbitrator_fees,
            'excess_amount_percent_details' => [
                'text' => 'Additional Arbitrator’s Fees @' . $excess_amount_percentage . '% of Excess Amount',
                'excess_amount_percentage' => $excess_amount_percentage,
                'excess_amount_percent' => $aaf_excess_amount_percent,
            ],
            'additional_arb_fees_details' => [
                'text' => 'Additional Arbitrator’s Fees of ' . $aaf_total_fee_percentage . '% of the above',
                'aaf_total_fee_percentage' => $aaf_total_fee_percentage,
                'aaf_total_fee_percent' => $aaf_total_fee_percent
            ]
        ];
    }

    public function calc_20_lakhs_to_1_crore($claim_amount, $arbitral_tribunal_strength)
    {
        $total_dispute = $claim_amount;
        $basic_amount = 2000000; // 20 lakh
        $excess_amount = $total_dispute - $basic_amount;

        $basic_arbitrator_fees = 97500; // Rs. 97500



        // Additional Arbitrator’s Fees @3% of Excess Amount
        $excess_amount_percentage = 3;
        $aaf_excess_amount_percent = ($excess_amount * $excess_amount_percentage) / 100;

        // Additional Arbitrator’s Fees of 25% of the total fee
        // ==========================================
        // Note => Add 25% only in sole arbitrator
        // ==========================================
        $aaf_total_fee_percent = 0;
        $aaf_total_fee_percentage = 25;
        // => If additional arbitrator fees is 1 then calculate 25% else do not calculate 25%
        if ($arbitral_tribunal_strength == 1) {
            $aaf_total_fee_percent = (($basic_arbitrator_fees + $aaf_excess_amount_percent) * $aaf_total_fee_percentage) / 100;
        }

        // Total (Basic + excess)
        $total_with_basic_plus_excess = $basic_arbitrator_fees + $aaf_excess_amount_percent;

        // Total Arbitrator’s Fees
        $total_arbitrator_fees = ($basic_arbitrator_fees + $aaf_excess_amount_percent + $aaf_total_fee_percent) * $arbitral_tribunal_strength;

        // Each party share 
        $each_party_share = $total_arbitrator_fees / 2;

        // Miscellaneous Expenses
        $miscellaneous_expenses = 20000; // Rs. 20000

        // Each party payable 
        $each_party_payable = $each_party_share + $miscellaneous_expenses;

        return [
            'total_arbitrator_fees' => $total_arbitrator_fees,
            'each_party_share_in_arb_fees' => $each_party_share,
            'administrative_charges' => $miscellaneous_expenses,
            'each_party_payable' => $each_party_payable,
            'basic_amount' => $basic_amount,
            'excess_amount' => $excess_amount,
            'total_with_basic_plus_excess' => $total_with_basic_plus_excess,
            'basic_arbitrator_fees' => $basic_arbitrator_fees,
            'excess_amount_percent_details' => [
                'text' => 'Additional Arbitrator’s Fees @' . $excess_amount_percentage . '% of Excess Amount',
                'excess_amount_percentage' => $excess_amount_percentage,
                'excess_amount_percent' => $aaf_excess_amount_percent,
            ],
            'additional_arb_fees_details' => [
                'text' => 'Additional Arbitrator’s Fees of ' . $aaf_total_fee_percentage . '% of the above',
                'aaf_total_fee_percentage' => $aaf_total_fee_percentage,
                'aaf_total_fee_percent' => $aaf_total_fee_percent
            ]
        ];
    }

    // Function for 1 Crore to 10 Crore
    function calc_1_crore_to_10_crore($claim_amount, $arbitral_tribunal_strength)
    {
        $total_dispute = $claim_amount;
        $basic_amount = 10000000; // 1 crore
        $excess_amount = $total_dispute - $basic_amount;

        $basic_arbitrator_fees = 337500; // Rs. 337500



        // Additional Arbitrator’s Fees @1.5% of Excess Amount
        $excess_amount_percentage = 1.5;
        $aaf_excess_amount_percent = ($excess_amount * $excess_amount_percentage) / 100;

        // Additional Arbitrator’s Fees of 25% of the total fee
        $aaf_total_fee_percent = 0;
        $aaf_total_fee_percentage = 25;
        // => If additional arbitrator fees is 1 then calculate 25% else do not calculate 25%
        if ($arbitral_tribunal_strength == 1) {
            $aaf_total_fee_percent = (($basic_arbitrator_fees + $aaf_excess_amount_percent) * $aaf_total_fee_percentage) / 100;
        }

        // Total (Basic + excess)
        $total_with_basic_plus_excess = $basic_arbitrator_fees + $aaf_excess_amount_percent;

        // Total Arbitrator’s Fees
        $total_arbitrator_fees = ($basic_arbitrator_fees + $aaf_excess_amount_percent + $aaf_total_fee_percent) * $arbitral_tribunal_strength;

        // Each party share 
        $each_party_share = $total_arbitrator_fees / 2;

        // Miscellaneous Expenses
        $miscellaneous_expenses = 40000; // Rs. 40000
        if ($total_dispute > 50000000) {
            $miscellaneous_expenses = 75000;
        }

        // Each party payable 
        $each_party_payable = $each_party_share + $miscellaneous_expenses;

        return [
            'total_arbitrator_fees' => $total_arbitrator_fees,
            'each_party_share_in_arb_fees' => $each_party_share,
            'administrative_charges' => $miscellaneous_expenses,
            'each_party_payable' => $each_party_payable,
            'basic_amount' => $basic_amount,
            'excess_amount' => $excess_amount,
            'basic_arbitrator_fees' => $basic_arbitrator_fees,
            'total_with_basic_plus_excess' => $total_with_basic_plus_excess,
            'excess_amount_percent_details' => [
                'text' => 'Additional Arbitrator’s Fees @' . $excess_amount_percentage . '% of Excess Amount',
                'excess_amount_percentage' => $excess_amount_percentage,
                'excess_amount_percent' => $aaf_excess_amount_percent,
            ],
            'additional_arb_fees_details' => [
                'text' => 'Additional Arbitrator’s Fees of ' . $aaf_total_fee_percentage . '% of the above',
                'aaf_total_fee_percentage' => $aaf_total_fee_percentage,
                'aaf_total_fee_percent' => $aaf_total_fee_percent
            ]
        ];
    }


    // ===========================================================================
    // Function for 10 Crore to 20 Crore
    function calc_10_crore_to_20_crore($claim_amount, $arbitral_tribunal_strength)
    {
        $total_dispute = $claim_amount;
        $basic_amount = 100000000; // 10 crore
        $excess_amount = $total_dispute - $basic_amount;

        $basic_arbitrator_fees = 1687500; // Rs. 1687500



        // Additional Arbitrator’s Fees @1% of Excess Amount
        $excess_amount_percentage = 1;
        $aaf_excess_amount_percent = ($excess_amount * $excess_amount_percentage) / 100;

        // Additional Arbitrator’s Fees of 25% of the total fee
        $aaf_total_fee_percent = 0;
        $aaf_total_fee_percentage = 25;
        // => If additional arbitrator fees is 1 then calculate 25% else do not calculate 25%
        if ($arbitral_tribunal_strength == 1) {
            $aaf_total_fee_percent = (($basic_arbitrator_fees + $aaf_excess_amount_percent) * $aaf_total_fee_percentage) / 100;
        }

        // Total (Basic + excess)
        $total_with_basic_plus_excess = $basic_arbitrator_fees + $aaf_excess_amount_percent;

        // Total Arbitrator’s Fees
        $total_arbitrator_fees = ($basic_arbitrator_fees + $aaf_excess_amount_percent + $aaf_total_fee_percent) * $arbitral_tribunal_strength;

        // Each party share 
        $each_party_share = $total_arbitrator_fees / 2;

        // Miscellaneous Expenses
        $miscellaneous_expenses = 100000; // Rs. 100000

        // Each party payable 
        $each_party_payable = $each_party_share + $miscellaneous_expenses;

        return [
            'total_arbitrator_fees' => $total_arbitrator_fees,
            'each_party_share_in_arb_fees' => $each_party_share,
            'administrative_charges' => $miscellaneous_expenses,
            'each_party_payable' => $each_party_payable,
            'basic_amount' => $basic_amount,
            'excess_amount' => $excess_amount,
            'basic_arbitrator_fees' => $basic_arbitrator_fees,
            'total_with_basic_plus_excess' => $total_with_basic_plus_excess,
            'excess_amount_percent_details' => [
                'text' => 'Additional Arbitrator’s Fees @' . $excess_amount_percentage . '% of Excess Amount',
                'excess_amount_percentage' => $excess_amount_percentage,
                'excess_amount_percent' => $aaf_excess_amount_percent,
            ],
            'additional_arb_fees_details' => [
                'text' => 'Additional Arbitrator’s Fees of ' . $aaf_total_fee_percentage . '% of the above',
                'aaf_total_fee_percentage' => $aaf_total_fee_percentage,
                'aaf_total_fee_percent' => $aaf_total_fee_percent
            ]
        ];
    }


    /*
        * Replace this function // 09 December 2019
        */

    // ===========================================================================
    // Function for above 20 Crore
    function calc_above_20_crore($claim_amount, $arbitral_tribunal_strength)
    {
        $total_dispute = $claim_amount;
        $basic_amount = 200000000; // 10 crore
        $excess_amount = $total_dispute - $basic_amount;

        $basic_arbitrator_fees = 2687500; // Rs. 2687500

        // Additional Arbitrator’s Fees @0.5% of Excess Amount
        $excess_amount_percentage = 0.5;
        $aaf_excess_amount_percent = ($excess_amount * $excess_amount_percentage) / 100;

        $ceiling_text = "";
        $total_with_basic_plus_excess_ceiling = 0;

        // Total (Basic + excess)
        $total_with_basic_plus_excess = $basic_arbitrator_fees + $aaf_excess_amount_percent;

        // $total_arb_excess_amount_without_ceil = "";

        if ($total_with_basic_plus_excess > 6000000) {
            $ceiling_text = "Arbitrator`s Fees as per ceiling applicable";
            // $total_arb_excess_amount_without_ceil = $aaf_excess_amount_percent;

            $total_with_basic_plus_excess_ceiling = 6000000;

            // $aaf_excess_amount_percent = 6000000;
        }

        // Additional Arbitrator’s Fees of 25% of the total fee
        $aaf_total_fee_percent = 0;
        $aaf_total_fee_percentage = 25;

        if ($total_with_basic_plus_excess > 6000000) {
            // => If additional arbitrator fees is 1 then calculate 25% else do not calculate 25%
            if ($arbitral_tribunal_strength == 1) {
                $aaf_total_fee_percent = (($total_with_basic_plus_excess_ceiling) * $aaf_total_fee_percentage) / 100;
            }
        } else {
            // => If additional arbitrator fees is 1 then calculate 25% else do not calculate 25%
            if ($arbitral_tribunal_strength == 1) {
                $aaf_total_fee_percent = (($basic_arbitrator_fees + $aaf_excess_amount_percent) * $aaf_total_fee_percentage) / 100;
            }
        }

        // Total Arbitrator’s Fees
        // $baf_aeap = basic_arbitrator_fees + aaf_excess_amount_percent;

        if ($total_with_basic_plus_excess > 6000000) {
            $total_arbitrator_fees = ($total_with_basic_plus_excess_ceiling + $aaf_total_fee_percent) * $arbitral_tribunal_strength;
        } else {
            $total_arbitrator_fees = ($basic_arbitrator_fees + $aaf_excess_amount_percent + $aaf_total_fee_percent) * $arbitral_tribunal_strength;
        }

        // if(baf_aeap > 3000000){
        //     total_arbitrator_fees = 3000000;

        //     // Additional Arbitrator’s Fees of 25% of the total fee
        //     $aaf_total_fee_percent = (total_arbitrator_fees * 25)/ 100;
        // }
        // else{

        //     // Additional Arbitrator’s Fees of 25% of the total fee
        //     $aaf_total_fee_percent = (baf_aeap * 25)/ 100;

        //     total_arbitrator_fees = baf_aeap + aaf_total_fee_percent;   
        // }

        // Each party share 
        $each_party_share = ($total_arbitrator_fees) / 2;

        // Miscellaneous Expenses
        if ($claim_amount > 500000000) {
            $miscellaneous_expenses = 250000; // Rs. 250000
        } else {
            $miscellaneous_expenses = 100000; // Rs. 100000
        }

        // Each party payable 
        $each_party_payable = $each_party_share + $miscellaneous_expenses;

        return [
            'total_arbitrator_fees' => $total_arbitrator_fees,
            'each_party_share_in_arb_fees' => $each_party_share,
            'administrative_charges' => $miscellaneous_expenses,
            'each_party_payable' => $each_party_payable,
            'basic_amount' => $basic_amount,
            'excess_amount' => $excess_amount,
            'basic_arbitrator_fees' => $basic_arbitrator_fees,
            'excess_amount_percent_details' => [
                'text' => 'Additional Arbitrator’s Fees @' . $excess_amount_percentage . '% of Excess Amount',
                'excess_amount_percentage' => $excess_amount_percentage,
                'excess_amount_percent' => $aaf_excess_amount_percent,
            ],
            'total_with_basic_plus_excess' => $total_with_basic_plus_excess,
            'excess' => [
                'ceiling_text' => $ceiling_text,
                'total_with_basic_plus_excess_ceiling' => $total_with_basic_plus_excess_ceiling
            ],
            'additional_arb_fees_details' => [
                'text' => 'Additional Arbitrator’s Fees @' . $aaf_total_fee_percentage . '% of the above',
                'aaf_total_fee_percentage' => $aaf_total_fee_percentage,
                'aaf_total_fee_percent' => $aaf_total_fee_percent
            ]
        ];
    }

    public function caluclate_fees_international($claim_amount, $arbitral_tribunal_strength)
    {
        if ($claim_amount < 1) {
            return false;
        }

        if ($claim_amount <= 50000) {
            $fees_result = $this->int_af_cost_calc_upto_50($claim_amount, $arbitral_tribunal_strength);
        }

        if ($claim_amount > 50000 && $claim_amount <= 100000) {
            $fees_result = $this->int_af_cost_calc_50_to_1_lakh($claim_amount, $arbitral_tribunal_strength);
        }
        if ($claim_amount > 100000 && $claim_amount <= 500000) {
            $fees_result = $this->int_af_cost_calc_1_to_5_lakh($claim_amount, $arbitral_tribunal_strength);
        }
        if ($claim_amount > 500000 && $claim_amount <= 1000000) {
            $fees_result = $this->int_af_cost_calc_5_to_10_lakh($claim_amount, $arbitral_tribunal_strength);
        }
        if ($claim_amount > 1000000 && $claim_amount <= 2000000) {
            $fees_result = $this->int_af_cost_calc_10_to_20_lakh($claim_amount, $arbitral_tribunal_strength);
        }
        if ($claim_amount > 2000000 && $claim_amount <= 5000000) {
            $fees_result = $this->int_af_cost_calc_20_to_50_lakh($claim_amount, $arbitral_tribunal_strength);
        }
        if ($claim_amount > 5000000 && $claim_amount <= 10000000) {
            $fees_result = $this->int_af_cost_calc_50_to_1_crore($claim_amount, $arbitral_tribunal_strength);
        }
        if ($claim_amount > 10000000 && $claim_amount <= 50000000) {
            $fees_result = $this->int_af_cost_calc_1_to_5_crore($claim_amount, $arbitral_tribunal_strength);
        }
        if ($claim_amount > 50000000 && $claim_amount <= 80000000) {
            $fees_result = $this->int_af_cost_calc_5_to_8_crore($claim_amount, $arbitral_tribunal_strength);
        }
        if ($claim_amount > 80000000 && $claim_amount <= 100000000) {
            $fees_result = $this->int_af_cost_calc_8_to_10_crore($claim_amount, $arbitral_tribunal_strength);
        }
        if ($claim_amount > 100000000) {
            $fees_result = $this->int_af_cost_calc_10_to_above($claim_amount, $arbitral_tribunal_strength);
        }

        return $fees_result;
    }


    /**
     * -----------------------------------------------------------
     * INTERNATIONAL CALCULATION
     * -----------------------------------------------------------
     */

    // Function for 50 thousand to 1 lakhs
    function int_af_cost_calc_upto_50($claim_amount, $arbitral_tribunal_strength)
    {

        $total_dispute = $claim_amount;
        $basic_amount = 0;
        $excess_amount = $total_dispute - $basic_amount;

        $basic_arbitrator_fees = 30000; // Rs. 30000

        // Additional Arbitrator’s Fees @6% of Excess Amount
        $excess_amount_percentage = 6;
        $aaf_excess_amount_percent = ($excess_amount * $excess_amount_percentage) / 100;

        // Additional Arbitrator’s Fees of 25% of the total fee
        // ==========================================
        // Note => Add 25% only in sole arbitrator
        // ==========================================
        $aaf_total_fee_percent = 0;
        $aaf_total_fee_percentage = 25;
        // => If additional arbitrator fees is 1 then calculate 25% else do not calculate 25%
        if ($arbitral_tribunal_strength == 1) {
            $aaf_total_fee_percent = (($basic_arbitrator_fees + $aaf_excess_amount_percent) * $aaf_total_fee_percentage) / 100;
        }

        // Total (Basic + excess)
        $total_with_basic_plus_excess = $basic_arbitrator_fees + $aaf_excess_amount_percent;

        // Total Arbitrator’s Fees
        $total_arbitrator_fees = ($basic_arbitrator_fees + $aaf_excess_amount_percent + $aaf_total_fee_percent) * $arbitral_tribunal_strength;

        // Each party share 
        $each_party_share_in_arb_fees = $total_arbitrator_fees / 2;

        // Calculate administrative charges
        $claim_amount_rupee = dollar_to_rupee($claim_amount);

        $administrative_charges = $this->caluclate_administrative_charges_international($claim_amount_rupee);

        // Each party payable 
        $each_party_payable = $each_party_share_in_arb_fees + $administrative_charges['each_party_share_dollar'];

        return [
            'total_arbitrator_fees_dollar' => $total_arbitrator_fees,
            'total_arbitrator_fees_rupee' => dollar_to_rupee($total_arbitrator_fees),
            'each_party_share_in_arb_fees_dollar' => $each_party_share_in_arb_fees,
            'each_party_share_in_arb_fees_rupee' => dollar_to_rupee($each_party_share_in_arb_fees),

            'basic_arbitrator_fees' => $basic_arbitrator_fees,

            'excess_amount_percent_details' => [
                'text' => 'Additional Arbitrator’s Fees @' . $excess_amount_percentage . '% of Excess Amount',
                'excess_amount_percentage' => $excess_amount_percentage,
                'excess_amount_percent' => $aaf_excess_amount_percent,
            ],
            'additional_arb_fees_details' => [
                'text' => 'Additional Arbitrator’s Fees of ' . $aaf_total_fee_percentage . '% of the above',
                'aaf_total_fee_percentage' => $aaf_total_fee_percentage,
                'aaf_total_fee_percent' => $aaf_total_fee_percent
            ],
            'total_with_basic_plus_excess' => $total_with_basic_plus_excess,

            'administrative_charges_rupee' => $administrative_charges['total_administrative_cost'],
            'administrative_charges_dollar' => $administrative_charges['total_administrative_cost_dollar'],
            'each_party_administrative_charges_rupee' => $administrative_charges['each_party_share'],
            'each_party_administrative_charges_dollar' => $administrative_charges['each_party_share_dollar'],

            'administrative_charges_details' => $administrative_charges,

            'each_party_payable_dollar' => $each_party_payable,
            'each_party_payable_rupee' => dollar_to_rupee($each_party_payable)
        ];
    }


    // Function for 50 thousand to 1 lakhs
    function int_af_cost_calc_50_to_1_lakh($claim_amount, $arbitral_tribunal_strength)
    {

        $total_dispute = $claim_amount;
        $basic_amount = 50000; // 50,000
        $excess_amount = $total_dispute - $basic_amount;

        $basic_arbitrator_fees = 30000; // Rs. 30000

        // Additional Arbitrator’s Fees @6% of Excess Amount
        $excess_amount_percentage = 6;
        $aaf_excess_amount_percent = ($excess_amount * $excess_amount_percentage) / 100;

        // Additional Arbitrator’s Fees of 25% of the total fee
        // ==========================================
        // Note => Add 25% only in sole arbitrator
        // ==========================================
        $aaf_total_fee_percent = 0;
        $aaf_total_fee_percentage = 25;
        // => If additional arbitrator fees is 1 then calculate 25% else do not calculate 25%
        if ($arbitral_tribunal_strength == 1) {
            $aaf_total_fee_percent = (($basic_arbitrator_fees + $aaf_excess_amount_percent) * $aaf_total_fee_percentage) / 100;
        }

        // Total (Basic + excess)
        $total_with_basic_plus_excess = $basic_arbitrator_fees + $aaf_excess_amount_percent;

        // Total Arbitrator’s Fees
        $total_arbitrator_fees = ($basic_arbitrator_fees + $aaf_excess_amount_percent + $aaf_total_fee_percent) * $arbitral_tribunal_strength;

        // Each party share 
        $each_party_share_in_arb_fees = $total_arbitrator_fees / 2;

        // Calculate administrative charges
        $claim_amount_rupee = dollar_to_rupee($claim_amount);

        $administrative_charges = $this->caluclate_administrative_charges_international($claim_amount_rupee);

        // Each party payable 
        $each_party_payable = $each_party_share_in_arb_fees + $administrative_charges['each_party_share_dollar'];

        return [
            'total_arbitrator_fees_dollar' => $total_arbitrator_fees,
            'total_arbitrator_fees_rupee' => dollar_to_rupee($total_arbitrator_fees),
            'each_party_share_in_arb_fees_dollar' => $each_party_share_in_arb_fees,
            'each_party_share_in_arb_fees_rupee' => dollar_to_rupee($each_party_share_in_arb_fees),

            'basic_amount' => $basic_amount,
            'excess_amount' => $excess_amount,
            'basic_arbitrator_fees' => $basic_arbitrator_fees,

            'excess_amount_percent_details' => [
                'text' => 'Additional Arbitrator’s Fees @' . $excess_amount_percentage . '% of Excess Amount',
                'excess_amount_percentage' => $excess_amount_percentage,
                'excess_amount_percent' => $aaf_excess_amount_percent,
            ],
            'additional_arb_fees_details' => [
                'text' => 'Additional Arbitrator’s Fees of ' . $aaf_total_fee_percentage . '% of the above',
                'aaf_total_fee_percentage' => $aaf_total_fee_percentage,
                'aaf_total_fee_percent' => $aaf_total_fee_percent
            ],
            'total_with_basic_plus_excess' => $total_with_basic_plus_excess,

            'administrative_charges_rupee' => $administrative_charges['total_administrative_cost'],
            'administrative_charges_dollar' => $administrative_charges['total_administrative_cost_dollar'],
            'each_party_administrative_charges_rupee' => $administrative_charges['each_party_share'],
            'each_party_administrative_charges_dollar' => $administrative_charges['each_party_share_dollar'],

            'administrative_charges_details' => $administrative_charges,

            'each_party_payable_dollar' => $each_party_payable,
            'each_party_payable_rupee' => dollar_to_rupee($each_party_payable)
        ];
    }


    // ===========================================================================
    // Function for 1 to 5 
    function int_af_cost_calc_1_to_5_lakh($claim_amount, $arbitral_tribunal_strength)
    {
        $total_dispute = $claim_amount;
        $basic_amount = 100000; // 1,00,000
        $excess_amount = $total_dispute - $basic_amount;

        $basic_arbitrator_fees = 7500; // Rs. 7,500

        // Additional Arbitrator’s Fees @3.5% of Excess Amount
        $excess_amount_percentage = 3.5;
        $aaf_excess_amount_percent = ($excess_amount * $excess_amount_percentage) / 100;

        // Additional Arbitrator’s Fees of 25% of the total fee
        // ==========================================
        // Note => Add 25% only in sole arbitrator
        // ==========================================
        $aaf_total_fee_percent = 0;
        $aaf_total_fee_percentage = 25;
        // => If additional arbitrator fees is 1 then calculate 25% else do not calculate 25%
        if ($arbitral_tribunal_strength == 1) {
            $aaf_total_fee_percent = (($basic_arbitrator_fees + $aaf_excess_amount_percent) * $aaf_total_fee_percentage) / 100;
        }

        // Total (Basic + excess)
        $total_with_basic_plus_excess = $basic_arbitrator_fees + $aaf_excess_amount_percent;

        // Total Arbitrator’s Fees
        $total_arbitrator_fees = ($basic_arbitrator_fees + $aaf_excess_amount_percent + $aaf_total_fee_percent) * $arbitral_tribunal_strength;

        // Each party share 
        $each_party_share_in_arb_fees = $total_arbitrator_fees / 2;


        // Calculate administrative charges
        $claim_amount_rupee = dollar_to_rupee($claim_amount);

        $administrative_charges = $this->caluclate_administrative_charges_international($claim_amount_rupee);

        // Each party payable 
        $each_party_payable = $each_party_share_in_arb_fees + $administrative_charges['each_party_share_dollar'];

        return [
            'total_arbitrator_fees_dollar' => $total_arbitrator_fees,
            'total_arbitrator_fees_rupee' => dollar_to_rupee($total_arbitrator_fees),
            'each_party_share_in_arb_fees_dollar' => $each_party_share_in_arb_fees,
            'each_party_share_in_arb_fees_rupee' => dollar_to_rupee($each_party_share_in_arb_fees),

            'basic_amount' => $basic_amount,
            'excess_amount' => $excess_amount,
            'basic_arbitrator_fees' => $basic_arbitrator_fees,

            'excess_amount_percent_details' => [
                'text' => 'Additional Arbitrator’s Fees @' . $excess_amount_percentage . '% of Excess Amount',
                'excess_amount_percentage' => $excess_amount_percentage,
                'excess_amount_percent' => $aaf_excess_amount_percent,
            ],

            'additional_arb_fees_details' => [
                'text' => 'Additional Arbitrator’s Fees of ' . $aaf_total_fee_percentage . '% of the above',
                'aaf_total_fee_percentage' => $aaf_total_fee_percentage,
                'aaf_total_fee_percent' => $aaf_total_fee_percent
            ],
            'total_with_basic_plus_excess' => $total_with_basic_plus_excess,

            'administrative_charges_rupee' => $administrative_charges['total_administrative_cost'],
            'administrative_charges_dollar' => $administrative_charges['total_administrative_cost_dollar'],
            'each_party_administrative_charges_rupee' => $administrative_charges['each_party_share'],
            'each_party_administrative_charges_dollar' => $administrative_charges['each_party_share_dollar'],

            'administrative_charges_details' => $administrative_charges,

            'each_party_payable_dollar' => $each_party_payable,
            'each_party_payable_rupee' => dollar_to_rupee($each_party_payable)
        ];
    }

    // ===========================================================================
    // Function for 5,00,000 to 10,00,000
    function int_af_cost_calc_5_to_10_lakh($claim_amount, $arbitral_tribunal_strength)
    {
        $total_dispute = $claim_amount;
        $basic_amount = 500000; // 5,00,000
        $excess_amount = $total_dispute - $basic_amount;

        $basic_arbitrator_fees = 21000; // Rs. 21,000

        // Additional Arbitrator’s Fees @2.5% of Excess Amount
        $excess_amount_percentage = 2.5;
        $aaf_excess_amount_percent = ($excess_amount * $excess_amount_percentage) / 100;

        // Additional Arbitrator’s Fees of 25% of the total fee
        // ==========================================
        // Note => Add 25% only in sole arbitrator
        // ==========================================
        $aaf_total_fee_percent = 0;
        $aaf_total_fee_percentage = 25;
        // => If additional arbitrator fees is 1 then calculate 25% else do not calculate 25%
        if ($arbitral_tribunal_strength == 1) {
            $aaf_total_fee_percent = (($basic_arbitrator_fees + $aaf_excess_amount_percent) * $aaf_total_fee_percentage) / 100;
        }

        // Total (Basic + excess)
        $total_with_basic_plus_excess = $basic_arbitrator_fees + $aaf_excess_amount_percent;

        // Total Arbitrator’s Fees
        $total_arbitrator_fees = ($basic_arbitrator_fees + $aaf_excess_amount_percent + $aaf_total_fee_percent) * $arbitral_tribunal_strength;

        // Each party share 
        $each_party_share_in_arb_fees = $total_arbitrator_fees / 2;

        // Calculate administrative charges
        $claim_amount_rupee = dollar_to_rupee($claim_amount);

        $administrative_charges = $this->caluclate_administrative_charges_international($claim_amount_rupee);

        // Each party payable 
        $each_party_payable = $each_party_share_in_arb_fees + $administrative_charges['each_party_share_dollar'];

        return [
            'total_arbitrator_fees_dollar' => $total_arbitrator_fees,
            'total_arbitrator_fees_rupee' => dollar_to_rupee($total_arbitrator_fees),
            'each_party_share_in_arb_fees_dollar' => $each_party_share_in_arb_fees,
            'each_party_share_in_arb_fees_rupee' => dollar_to_rupee($each_party_share_in_arb_fees),

            'basic_amount' => $basic_amount,
            'excess_amount' => $excess_amount,
            'basic_arbitrator_fees' => $basic_arbitrator_fees,

            'excess_amount_percent_details' => [
                'text' => 'Additional Arbitrator’s Fees @' . $excess_amount_percentage . '% of Excess Amount',
                'excess_amount_percentage' => $excess_amount_percentage,
                'excess_amount_percent' => $aaf_excess_amount_percent,
            ],

            'additional_arb_fees_details' => [
                'text' => 'Additional Arbitrator’s Fees of ' . $aaf_total_fee_percentage . '% of the above',
                'aaf_total_fee_percentage' => $aaf_total_fee_percentage,
                'aaf_total_fee_percent' => $aaf_total_fee_percent
            ],
            'total_with_basic_plus_excess' => $total_with_basic_plus_excess,

            'administrative_charges_rupee' => $administrative_charges['total_administrative_cost'],
            'administrative_charges_dollar' => $administrative_charges['total_administrative_cost_dollar'],
            'each_party_administrative_charges_rupee' => $administrative_charges['each_party_share'],
            'each_party_administrative_charges_dollar' => $administrative_charges['each_party_share_dollar'],

            'administrative_charges_details' => $administrative_charges,

            'each_party_payable_dollar' => $each_party_payable,
            'each_party_payable_rupee' => dollar_to_rupee($each_party_payable)
        ];
    }

    // ===========================================================================
    // Function for 10,00,000 to 20,00,000
    function int_af_cost_calc_10_to_20_lakh($claim_amount, $arbitral_tribunal_strength)
    {
        $total_dispute = $claim_amount;
        $basic_amount = 1000000; // 10,00,000
        $excess_amount = $total_dispute - $basic_amount;

        $basic_arbitrator_fees = 33500; // Rs. 33,500

        // Additional Arbitrator’s Fees @1.5% of Excess Amount
        $excess_amount_percentage = 1.5;
        $aaf_excess_amount_percent = ($excess_amount * $excess_amount_percentage) / 100;

        // Additional Arbitrator’s Fees of 25% of the total fee
        // ==========================================
        // Note => Add 25% only in sole arbitrator
        // ==========================================
        $aaf_total_fee_percent = 0;
        $aaf_total_fee_percentage = 25;
        // => If additional arbitrator fees is 1 then calculate 25% else do not calculate 25%
        if ($arbitral_tribunal_strength == 1) {
            $aaf_total_fee_percent = (($basic_arbitrator_fees + $aaf_excess_amount_percent) * $aaf_total_fee_percentage) / 100;
        }

        // Total (Basic + excess)
        $total_with_basic_plus_excess = $basic_arbitrator_fees + $aaf_excess_amount_percent;

        // Total Arbitrator’s Fees
        $total_arbitrator_fees = ($basic_arbitrator_fees + $aaf_excess_amount_percent + $aaf_total_fee_percent) * $arbitral_tribunal_strength;

        // Each party share 
        $each_party_share_in_arb_fees = $total_arbitrator_fees / 2;

        // Calculate administrative charges
        $claim_amount_rupee = dollar_to_rupee($claim_amount);

        $administrative_charges = $this->caluclate_administrative_charges_international($claim_amount_rupee);

        // Each party payable 
        $each_party_payable = $each_party_share_in_arb_fees + $administrative_charges['each_party_share_dollar'];

        return [
            'total_arbitrator_fees_dollar' => $total_arbitrator_fees,
            'total_arbitrator_fees_rupee' => dollar_to_rupee($total_arbitrator_fees),
            'each_party_share_in_arb_fees_dollar' => $each_party_share_in_arb_fees,
            'each_party_share_in_arb_fees_rupee' => dollar_to_rupee($each_party_share_in_arb_fees),

            'basic_amount' => $basic_amount,
            'excess_amount' => $excess_amount,
            'basic_arbitrator_fees' => $basic_arbitrator_fees,

            'excess_amount_percent_details' => [
                'text' => 'Additional Arbitrator’s Fees @' . $excess_amount_percentage . '% of Excess Amount',
                'excess_amount_percentage' => $excess_amount_percentage,
                'excess_amount_percent' => $aaf_excess_amount_percent,
            ],

            'additional_arb_fees_details' => [
                'text' => 'Additional Arbitrator’s Fees of ' . $aaf_total_fee_percentage . '% of the above',
                'aaf_total_fee_percentage' => $aaf_total_fee_percentage,
                'aaf_total_fee_percent' => $aaf_total_fee_percent
            ],
            'total_with_basic_plus_excess' => $total_with_basic_plus_excess,

            'administrative_charges_rupee' => $administrative_charges['total_administrative_cost'],
            'administrative_charges_dollar' => $administrative_charges['total_administrative_cost_dollar'],
            'each_party_administrative_charges_rupee' => $administrative_charges['each_party_share'],
            'each_party_administrative_charges_dollar' => $administrative_charges['each_party_share_dollar'],

            'administrative_charges_details' => $administrative_charges,

            'each_party_payable_dollar' => $each_party_payable,
            'each_party_payable_rupee' => dollar_to_rupee($each_party_payable)
        ];
    }

    // ===========================================================================
    // Function for 20,00,000 to 50,00,000
    function int_af_cost_calc_20_to_50_lakh($claim_amount, $arbitral_tribunal_strength)
    {
        $total_dispute = $claim_amount;
        $basic_amount = 2000000; // 20,00,000
        $excess_amount = $total_dispute - $basic_amount;

        $basic_arbitrator_fees = 48500; // Rs. 48,500

        // Additional Arbitrator’s Fees @0.75% of Excess Amount
        $excess_amount_percentage = 0.75;
        $aaf_excess_amount_percent = ($excess_amount * $excess_amount_percentage) / 100;

        // Additional Arbitrator’s Fees of 25% of the total fee
        // ==========================================
        // Note => Add 25% only in sole arbitrator
        // ==========================================
        $aaf_total_fee_percent = 0;
        $aaf_total_fee_percentage = 25;
        // => If additional arbitrator fees is 1 then calculate 25% else do not calculate 25%
        if ($arbitral_tribunal_strength == 1) {
            $aaf_total_fee_percent = (($basic_arbitrator_fees + $aaf_excess_amount_percent) * $aaf_total_fee_percentage) / 100;
        }

        // Total (Basic + excess)
        $total_with_basic_plus_excess = $basic_arbitrator_fees + $aaf_excess_amount_percent;

        // Total Arbitrator’s Fees
        $total_arbitrator_fees = ($basic_arbitrator_fees + $aaf_excess_amount_percent + $aaf_total_fee_percent) * $arbitral_tribunal_strength;

        // Each party share 
        $each_party_share_in_arb_fees = $total_arbitrator_fees / 2;

        // Calculate administrative charges
        $claim_amount_rupee = dollar_to_rupee($claim_amount);

        $administrative_charges = $this->caluclate_administrative_charges_international($claim_amount_rupee);

        // Each party payable 
        $each_party_payable = $each_party_share_in_arb_fees + $administrative_charges['each_party_share_dollar'];

        return [
            'total_arbitrator_fees_dollar' => $total_arbitrator_fees,
            'total_arbitrator_fees_rupee' => dollar_to_rupee($total_arbitrator_fees),
            'each_party_share_in_arb_fees_dollar' => $each_party_share_in_arb_fees,
            'each_party_share_in_arb_fees_rupee' => dollar_to_rupee($each_party_share_in_arb_fees),

            'basic_amount' => $basic_amount,
            'excess_amount' => $excess_amount,
            'basic_arbitrator_fees' => $basic_arbitrator_fees,

            'excess_amount_percent_details' => [
                'text' => 'Additional Arbitrator’s Fees @' . $excess_amount_percentage . '% of Excess Amount',
                'excess_amount_percentage' => $excess_amount_percentage,
                'excess_amount_percent' => $aaf_excess_amount_percent,
            ],

            'additional_arb_fees_details' => [
                'text' => 'Additional Arbitrator’s Fees of ' . $aaf_total_fee_percentage . '% of the above',
                'aaf_total_fee_percentage' => $aaf_total_fee_percentage,
                'aaf_total_fee_percent' => $aaf_total_fee_percent
            ],
            'total_with_basic_plus_excess' => $total_with_basic_plus_excess,

            'administrative_charges_rupee' => $administrative_charges['total_administrative_cost'],
            'administrative_charges_dollar' => $administrative_charges['total_administrative_cost_dollar'],
            'each_party_administrative_charges_rupee' => $administrative_charges['each_party_share'],
            'each_party_administrative_charges_dollar' => $administrative_charges['each_party_share_dollar'],

            'administrative_charges_details' => $administrative_charges,

            'each_party_payable_dollar' => $each_party_payable,
            'each_party_payable_rupee' => dollar_to_rupee($each_party_payable)
        ];
    }

    // ===========================================================================
    // Function for 50,00,000 to 1,00,00,000
    function int_af_cost_calc_50_to_1_crore($claim_amount, $arbitral_tribunal_strength)
    {
        $total_dispute = $claim_amount;
        $basic_amount = 5000000; //50,00,000
        $excess_amount = $total_dispute - $basic_amount;

        $basic_arbitrator_fees = 71000; // Rs. 71,000

        // Additional Arbitrator’s Fees @0.35% of Excess Amount
        $excess_amount_percentage = 3.5;
        $aaf_excess_amount_percent = ($excess_amount * $excess_amount_percentage) / 100;

        // Additional Arbitrator’s Fees of 25% of the total fee
        // ==========================================
        // Note => Add 25% only in sole arbitrator
        // ==========================================
        $aaf_total_fee_percent = 0;
        $aaf_total_fee_percentage = 25;
        // => If additional arbitrator fees is 1 then calculate 25% else do not calculate 25%
        if ($arbitral_tribunal_strength == 1) {
            $aaf_total_fee_percent = (($basic_arbitrator_fees + $aaf_excess_amount_percent) * $aaf_total_fee_percentage) / 100;
        }

        // Total (Basic + excess)
        $total_with_basic_plus_excess = $basic_arbitrator_fees + $aaf_excess_amount_percent;

        // Total Arbitrator’s Fees
        $total_arbitrator_fees = ($basic_arbitrator_fees + $aaf_excess_amount_percent + $aaf_total_fee_percent) * $arbitral_tribunal_strength;

        // Each party share 
        $each_party_share_in_arb_fees = $total_arbitrator_fees / 2;

        // Calculate administrative charges
        $claim_amount_rupee = dollar_to_rupee($claim_amount);

        $administrative_charges = $this->caluclate_administrative_charges_international($claim_amount_rupee);

        // Each party payable 
        $each_party_payable = $each_party_share_in_arb_fees + $administrative_charges['each_party_share_dollar'];

        return [
            'total_arbitrator_fees_dollar' => $total_arbitrator_fees,
            'total_arbitrator_fees_rupee' => dollar_to_rupee($total_arbitrator_fees),
            'each_party_share_in_arb_fees_dollar' => $each_party_share_in_arb_fees,
            'each_party_share_in_arb_fees_rupee' => dollar_to_rupee($each_party_share_in_arb_fees),

            'basic_amount' => $basic_amount,
            'excess_amount' => $excess_amount,
            'basic_arbitrator_fees' => $basic_arbitrator_fees,

            'excess_amount_percent_details' => [
                'text' => 'Additional Arbitrator’s Fees @' . $excess_amount_percentage . '% of Excess Amount',
                'excess_amount_percentage' => $excess_amount_percentage,
                'excess_amount_percent' => $aaf_excess_amount_percent,
            ],

            'additional_arb_fees_details' => [
                'text' => 'Additional Arbitrator’s Fees of ' . $aaf_total_fee_percentage . '% of the above',
                'aaf_total_fee_percentage' => $aaf_total_fee_percentage,
                'aaf_total_fee_percent' => $aaf_total_fee_percent
            ],
            'total_with_basic_plus_excess' => $total_with_basic_plus_excess,

            'administrative_charges_rupee' => $administrative_charges['total_administrative_cost'],
            'administrative_charges_dollar' => $administrative_charges['total_administrative_cost_dollar'],
            'each_party_administrative_charges_rupee' => $administrative_charges['each_party_share'],
            'each_party_administrative_charges_dollar' => $administrative_charges['each_party_share_dollar'],

            'administrative_charges_details' => $administrative_charges,

            'each_party_payable_dollar' => $each_party_payable,
            'each_party_payable_rupee' => dollar_to_rupee($each_party_payable)
        ];
    }


    // ===========================================================================
    // Function for 1,00,00,000 to 5,00,00,000
    function int_af_cost_calc_1_to_5_crore($claim_amount, $arbitral_tribunal_strength)
    {
        $total_dispute = $claim_amount;
        $basic_amount = 10000000; // 1,00,00,000
        $excess_amount = $total_dispute - $basic_amount;

        $basic_arbitrator_fees = 88500; // Rs. 88,500

        // Additional Arbitrator’s Fees @0.15% of Excess Amount
        $excess_amount_percentage = 0.15;
        $aaf_excess_amount_percent = ($excess_amount * $excess_amount_percentage) / 100;

        // Additional Arbitrator’s Fees of 25% of the total fee
        // ==========================================
        // Note => Add 25% only in sole arbitrator
        // ==========================================
        $aaf_total_fee_percent = 0;
        $aaf_total_fee_percentage = 25;
        // => If additional arbitrator fees is 1 then calculate 25% else do not calculate 25%
        if ($arbitral_tribunal_strength == 1) {
            $aaf_total_fee_percent = (($basic_arbitrator_fees + $aaf_excess_amount_percent) * $aaf_total_fee_percentage) / 100;
        }

        // Total (Basic + excess)
        $total_with_basic_plus_excess = $basic_arbitrator_fees + $aaf_excess_amount_percent;

        // Total Arbitrator’s Fees
        $total_arbitrator_fees = ($basic_arbitrator_fees + $aaf_excess_amount_percent + $aaf_total_fee_percent) * $arbitral_tribunal_strength;

        // Each party share 
        $each_party_share_in_arb_fees = $total_arbitrator_fees / 2;

        // Calculate administrative charges
        $claim_amount_rupee = dollar_to_rupee($claim_amount);

        $administrative_charges = $this->caluclate_administrative_charges_international($claim_amount_rupee);

        // Each party payable 
        $each_party_payable = $each_party_share_in_arb_fees + $administrative_charges['each_party_share_dollar'];

        return [
            'total_arbitrator_fees_dollar' => $total_arbitrator_fees,
            'total_arbitrator_fees_rupee' => dollar_to_rupee($total_arbitrator_fees),
            'each_party_share_in_arb_fees_dollar' => $each_party_share_in_arb_fees,
            'each_party_share_in_arb_fees_rupee' => dollar_to_rupee($each_party_share_in_arb_fees),

            'basic_amount' => $basic_amount,
            'excess_amount' => $excess_amount,
            'basic_arbitrator_fees' => $basic_arbitrator_fees,

            'excess_amount_percent_details' => [
                'text' => 'Additional Arbitrator’s Fees @' . $excess_amount_percentage . '% of Excess Amount',
                'excess_amount_percentage' => $excess_amount_percentage,
                'excess_amount_percent' => $aaf_excess_amount_percent,
            ],

            'additional_arb_fees_details' => [
                'text' => 'Additional Arbitrator’s Fees of ' . $aaf_total_fee_percentage . '% of the above',
                'aaf_total_fee_percentage' => $aaf_total_fee_percentage,
                'aaf_total_fee_percent' => $aaf_total_fee_percent
            ],
            'total_with_basic_plus_excess' => $total_with_basic_plus_excess,

            'administrative_charges_rupee' => $administrative_charges['total_administrative_cost'],
            'administrative_charges_dollar' => $administrative_charges['total_administrative_cost_dollar'],
            'each_party_administrative_charges_rupee' => $administrative_charges['each_party_share'],
            'each_party_administrative_charges_dollar' => $administrative_charges['each_party_share_dollar'],

            'administrative_charges_details' => $administrative_charges,

            'each_party_payable_dollar' => $each_party_payable,
            'each_party_payable_rupee' => dollar_to_rupee($each_party_payable)
        ];
    }


    // ===========================================================================
    // Function for 5,00,00,000 to 8,00,00,000
    function int_af_cost_calc_5_to_8_crore($claim_amount, $arbitral_tribunal_strength)
    {
        $total_dispute = $claim_amount;
        $basic_amount = 50000000; //5,00,00,000
        $excess_amount = $total_dispute - $basic_amount;

        $basic_arbitrator_fees = 94500; // Rs. 94,500

        // Additional Arbitrator’s Fees @0.075% of Excess 
        $excess_amount_percentage = 0.075;
        $aaf_excess_amount_percent = ($excess_amount * $excess_amount_percentage) / 100;

        // Additional Arbitrator’s Fees of 25% of the total fee
        // ==========================================
        // Note => Add 25% only in sole arbitrator
        // ==========================================
        $aaf_total_fee_percent = 0;
        $aaf_total_fee_percentage = 25;
        // => If additional arbitrator fees is 1 then calculate 25% else do not calculate 25%
        if ($arbitral_tribunal_strength == 1) {
            $aaf_total_fee_percent = (($basic_arbitrator_fees + $aaf_excess_amount_percent) * $aaf_total_fee_percentage) / 100;
        }

        // Total (Basic + excess)
        $total_with_basic_plus_excess = $basic_arbitrator_fees + $aaf_excess_amount_percent;

        // Total Arbitrator’s Fees
        $total_arbitrator_fees = ($basic_arbitrator_fees + $aaf_excess_amount_percent + $aaf_total_fee_percent) * $arbitral_tribunal_strength;

        // Each party share 
        $each_party_share_in_arb_fees = $total_arbitrator_fees / 2;

        // Calculate administrative charges
        $claim_amount_rupee = dollar_to_rupee($claim_amount);

        $administrative_charges = $this->caluclate_administrative_charges_international($claim_amount_rupee);

        // Each party payable 
        $each_party_payable = $each_party_share_in_arb_fees + $administrative_charges['each_party_share_dollar'];

        return [
            'total_arbitrator_fees_dollar' => $total_arbitrator_fees,
            'total_arbitrator_fees_rupee' => dollar_to_rupee($total_arbitrator_fees),
            'each_party_share_in_arb_fees_dollar' => $each_party_share_in_arb_fees,
            'each_party_share_in_arb_fees_rupee' => dollar_to_rupee($each_party_share_in_arb_fees),

            'basic_amount' => $basic_amount,
            'excess_amount' => $excess_amount,
            'basic_arbitrator_fees' => $basic_arbitrator_fees,

            'excess_amount_percent_details' => [
                'text' => 'Additional Arbitrator’s Fees @' . $excess_amount_percentage . '% of Excess Amount',
                'excess_amount_percentage' => $excess_amount_percentage,
                'excess_amount_percent' => $aaf_excess_amount_percent,
            ],

            'additional_arb_fees_details' => [
                'text' => 'Additional Arbitrator’s Fees of ' . $aaf_total_fee_percentage . '% of the above',
                'aaf_total_fee_percentage' => $aaf_total_fee_percentage,
                'aaf_total_fee_percent' => $aaf_total_fee_percent
            ],
            'total_with_basic_plus_excess' => $total_with_basic_plus_excess,

            'administrative_charges_rupee' => $administrative_charges['total_administrative_cost'],
            'administrative_charges_dollar' => $administrative_charges['total_administrative_cost_dollar'],
            'each_party_administrative_charges_rupee' => $administrative_charges['each_party_share'],
            'each_party_administrative_charges_dollar' => $administrative_charges['each_party_share_dollar'],

            'administrative_charges_details' => $administrative_charges,

            'each_party_payable_dollar' => $each_party_payable,
            'each_party_payable_rupee' => dollar_to_rupee($each_party_payable)
        ];
    }


    // ===========================================================================
    // Function for 8,00,00,000 to 10,00,00,000
    function int_af_cost_calc_8_to_10_crore($claim_amount, $arbitral_tribunal_strength)
    {
        $total_dispute = $claim_amount;
        $basic_amount = 80000000; //8,00,00,000
        $excess_amount = $total_dispute - $basic_amount;

        $basic_arbitrator_fees = 117000; // Rs. 1,17,000

        // Additional Arbitrator’s Fees @0.03% of Excess Amount
        $excess_amount_percentage = 0.03;
        $aaf_excess_amount_percent = ($excess_amount * $excess_amount_percentage) / 100;

        // Additional Arbitrator’s Fees of 25% of the total fee
        // ==========================================
        // Note => Add 25% only in sole arbitrator
        // ==========================================
        $aaf_total_fee_percent = 0;
        $aaf_total_fee_percentage = 25;
        // => If additional arbitrator fees is 1 then calculate 25% else do not calculate 25%
        if ($arbitral_tribunal_strength == 1) {
            $aaf_total_fee_percent = (($basic_arbitrator_fees + $aaf_excess_amount_percent) * $aaf_total_fee_percentage) / 100;
        }

        // Total (Basic + excess)
        $total_with_basic_plus_excess = $basic_arbitrator_fees + $aaf_excess_amount_percent;

        // Total Arbitrator’s Fees
        $total_arbitrator_fees = ($basic_arbitrator_fees + $aaf_excess_amount_percent + $aaf_total_fee_percent) * $arbitral_tribunal_strength;

        // Each party share 
        $each_party_share_in_arb_fees = $total_arbitrator_fees / 2;

        // Calculate administrative charges
        $claim_amount_rupee = dollar_to_rupee($claim_amount);

        $administrative_charges = $this->caluclate_administrative_charges_international($claim_amount_rupee);

        // Each party payable 
        $each_party_payable = $each_party_share_in_arb_fees + $administrative_charges['each_party_share_dollar'];

        return [
            'total_arbitrator_fees_dollar' => $total_arbitrator_fees,
            'total_arbitrator_fees_rupee' => dollar_to_rupee($total_arbitrator_fees),
            'each_party_share_in_arb_fees_dollar' => $each_party_share_in_arb_fees,
            'each_party_share_in_arb_fees_rupee' => dollar_to_rupee($each_party_share_in_arb_fees),

            'basic_amount' => $basic_amount,
            'excess_amount' => $excess_amount,
            'basic_arbitrator_fees' => $basic_arbitrator_fees,

            'excess_amount_percent_details' => [
                'text' => 'Additional Arbitrator’s Fees @' . $excess_amount_percentage . '% of Excess Amount',
                'excess_amount_percentage' => $excess_amount_percentage,
                'excess_amount_percent' => $aaf_excess_amount_percent,
            ],

            'additional_arb_fees_details' => [
                'text' => 'Additional Arbitrator’s Fees of ' . $aaf_total_fee_percentage . '% of the above',
                'aaf_total_fee_percentage' => $aaf_total_fee_percentage,
                'aaf_total_fee_percent' => $aaf_total_fee_percent
            ],
            'total_with_basic_plus_excess' => $total_with_basic_plus_excess,

            'administrative_charges_rupee' => $administrative_charges['total_administrative_cost'],
            'administrative_charges_dollar' => $administrative_charges['total_administrative_cost_dollar'],
            'each_party_administrative_charges_rupee' => $administrative_charges['each_party_share'],
            'each_party_administrative_charges_dollar' => $administrative_charges['each_party_share_dollar'],

            'administrative_charges_details' => $administrative_charges,

            'each_party_payable_dollar' => $each_party_payable,
            'each_party_payable_rupee' => dollar_to_rupee($each_party_payable)
        ];
    }


    // ===========================================================================
    // Function for 10,00,00,000 to above
    function int_af_cost_calc_10_to_above($claim_amount, $arbitral_tribunal_strength)
    {
        $total_dispute = $claim_amount;
        $basic_amount = 100000000; // 10,00,00,000
        $excess_amount = $total_dispute - $basic_amount;

        $basic_arbitrator_fees = 123000; // Rs. 1,23,000

        // Additional Arbitrator’s Fees @0.02% of Excess Amount
        $excess_amount_percentage = 0.02;
        $aaf_excess_amount_percent = ($excess_amount * $excess_amount_percentage) / 100;

        // Additional Arbitrator’s Fees of 25% of the total fee
        // ==========================================
        // Note => Add 25% only in sole arbitrator
        // ==========================================
        $aaf_total_fee_percent = 0;
        $aaf_total_fee_percentage = 25;
        // => If additional arbitrator fees is 1 then calculate 25% else do not calculate 25%
        if ($arbitral_tribunal_strength == 1) {
            $aaf_total_fee_percent = (($basic_arbitrator_fees + $aaf_excess_amount_percent) * $aaf_total_fee_percentage) / 100;
        }

        // Total (Basic + excess)
        $total_with_basic_plus_excess = $basic_arbitrator_fees + $aaf_excess_amount_percent;

        // Total Arbitrator’s Fees
        $total_arbitrator_fees = ($basic_arbitrator_fees + $aaf_excess_amount_percent + $aaf_total_fee_percent) * $arbitral_tribunal_strength;

        // Each party share 
        $each_party_share_in_arb_fees = $total_arbitrator_fees / 2;

        // Calculate administrative charges
        $claim_amount_rupee = dollar_to_rupee($claim_amount);

        $administrative_charges = $this->caluclate_administrative_charges_international($claim_amount_rupee);

        // Each party payable 
        $each_party_payable = $each_party_share_in_arb_fees + $administrative_charges['each_party_share_dollar'];

        return [
            'total_arbitrator_fees_dollar' => $total_arbitrator_fees,
            'total_arbitrator_fees_rupee' => dollar_to_rupee($total_arbitrator_fees),
            'each_party_share_in_arb_fees_dollar' => $each_party_share_in_arb_fees,
            'each_party_share_in_arb_fees_rupee' => dollar_to_rupee($each_party_share_in_arb_fees),

            'basic_amount' => $basic_amount,
            'excess_amount' => $excess_amount,
            'basic_arbitrator_fees' => $basic_arbitrator_fees,

            'excess_amount_percent_details' => [
                'text' => 'Additional Arbitrator’s Fees @' . $excess_amount_percentage . '% of Excess Amount',
                'excess_amount_percentage' => $excess_amount_percentage,
                'excess_amount_percent' => $aaf_excess_amount_percent,
            ],

            'additional_arb_fees_details' => [
                'text' => 'Additional Arbitrator’s Fees of ' . $aaf_total_fee_percentage . '% of the above',
                'aaf_total_fee_percentage' => $aaf_total_fee_percentage,
                'aaf_total_fee_percent' => $aaf_total_fee_percent
            ],
            'total_with_basic_plus_excess' => $total_with_basic_plus_excess,

            'administrative_charges_rupee' => $administrative_charges['total_administrative_cost'],
            'administrative_charges_dollar' => $administrative_charges['total_administrative_cost_dollar'],
            'each_party_administrative_charges_rupee' => $administrative_charges['each_party_share'],
            'each_party_administrative_charges_dollar' => $administrative_charges['each_party_share_dollar'],

            'administrative_charges_details' => $administrative_charges,

            'each_party_payable_dollar' => $each_party_payable,
            'each_party_payable_rupee' => dollar_to_rupee($each_party_payable)
        ];
    }

    // Internationl Administrative Charges Calculation Start ==================

    // ==========================================================================

    public function
    caluclate_administrative_charges_international($claim_amount)
    {
        if ($claim_amount < 1) {
            return false;
        }

        if ($claim_amount <= 1000000) {
            $fees_result = $this->int_arb_arb_calc_upto_10_lakhs($claim_amount);
        }

        if (
            $claim_amount > 1000000 && $claim_amount <= 5000000
        ) {
            $fees_result = $this->int_arb_arb_calc_10_lakhs_to_50_lakhs($claim_amount);
        }
        if (
            $claim_amount > 5000000 && $claim_amount <= 10000000
        ) {
            $fees_result = $this->int_arb_arb_calc_50_lakhs_to_1_crore($claim_amount);
        }
        if (
            $claim_amount > 10000000 && $claim_amount <= 100000000
        ) {
            $fees_result = $this->int_arb_arb_calc_1_crore_to_10_crore($claim_amount);
        }
        if ($claim_amount > 100000000) {
            $fees_result = $this->int_arb_arb_calc_above_10_crore($claim_amount);
        }

        return $fees_result;
    }

    // Function for 10 lakhs to 50 lakhs
    function int_arb_arb_calc_upto_10_lakhs($claim_amount)
    {
        $total_dispute = $claim_amount + $this->fixed_administrative_cost;

        $basic_administrative_cost = 30000; // Rs. 30000

        // Total Arbitrator’s Fees
        $total_administrative_cost = $basic_administrative_cost;

        // Each party share 
        $each_party_share = $total_administrative_cost / 2;

        // Each party payable 
        $each_party_payable = $each_party_share;

        return [
            'claim_amount_rupee' => $claim_amount,
            'total_administrative_cost' => $total_administrative_cost,
            'total_administrative_cost_dollar' => rupee_to_dollar($total_administrative_cost),
            'each_party_share' => $each_party_share,
            'each_party_share_dollar' => rupee_to_dollar($each_party_share),
            'each_party_payable' => $each_party_payable,
            'each_party_payable_dollar' => rupee_to_dollar($each_party_payable),

            'basic_administrative_cost' => $basic_administrative_cost,
        ];
    }

    // Function for 10 lakhs to 50 lakhs
    function int_arb_arb_calc_10_lakhs_to_50_lakhs($claim_amount)
    {
        $total_dispute = $claim_amount + $this->fixed_administrative_cost;
        $basic_amount = 1000000; // 10 lakh
        $excess_amount = $total_dispute - $basic_amount;

        $basic_administrative_cost = 30000; // Rs. 30000

        // Additional Arbitrator’s Fees @1% of Excess Amount
        $excess_amount_percent = 1;
        $aaf_excess_amount_percent = ($excess_amount * $excess_amount_percent) / 100;

        // Total Arbitrator’s Fees
        $total_administrative_cost = $basic_administrative_cost + $aaf_excess_amount_percent;

        // Each party share 
        $each_party_share = $total_administrative_cost / 2;

        // Each party payable 
        $each_party_payable = $each_party_share;

        return [
            'claim_amount_rupee' => $claim_amount,
            'total_administrative_cost' => $total_administrative_cost,
            'total_administrative_cost_dollar' => rupee_to_dollar($total_administrative_cost),
            'each_party_share' => $each_party_share,
            'each_party_share_dollar' => rupee_to_dollar($each_party_share),
            'each_party_payable' => $each_party_payable,
            'each_party_payable_dollar' => rupee_to_dollar($each_party_payable),

            'basic_amount' => $basic_amount,
            'excess_amount' => $excess_amount,
            'basic_administrative_cost' => $basic_administrative_cost,
            'excess_amount_percent' => $excess_amount_percent,
            'aaf_excess_amount_percent' => $aaf_excess_amount_percent,
            'total_administrative_cost' => $total_administrative_cost,
        ];
    }


    // ===========================================================================
    // Function for 50 lakhs to 1 crore
    function int_arb_arb_calc_50_lakhs_to_1_crore($claim_amount)
    {
        $total_dispute = $claim_amount + $this->fixed_administrative_cost;
        $basic_amount = 5000000; // 50 lakh
        $excess_amount = $total_dispute - $basic_amount;

        $basic_administrative_cost = 7000000; // Rs. 70,00,000

        // Additional Arbitrator’s Fees @0.5% of Excess Amount
        $excess_amount_percent = 0.5;
        $aaf_excess_amount_percent = ($excess_amount * $excess_amount_percent) / 100;

        // Total Arbitrator’s Fees
        $total_administrative_cost = $basic_administrative_cost + $aaf_excess_amount_percent;

        // Each party share 
        $each_party_share = $total_administrative_cost / 2;


        // Each party payable 
        $each_party_payable = $each_party_share;

        return [
            'claim_amount_rupee' => $claim_amount,
            'total_administrative_cost' => $total_administrative_cost,
            'total_administrative_cost_dollar' => rupee_to_dollar($total_administrative_cost),
            'each_party_share' => $each_party_share,
            'each_party_share_dollar' => rupee_to_dollar($each_party_share),
            'each_party_payable' => $each_party_payable,
            'each_party_payable_dollar' => rupee_to_dollar($each_party_payable),

            'basic_amount' => $basic_amount,
            'excess_amount' => $excess_amount,
            'basic_administrative_cost' => $basic_administrative_cost,
            'excess_amount_percent' => $excess_amount_percent,
            'aaf_excess_amount_percent' => $aaf_excess_amount_percent,
            'total_administrative_cost' => $total_administrative_cost,
        ];
    }

    // ===========================================================================
    // Function for 1 Crore to 10 Crore
    function int_arb_arb_calc_1_crore_to_10_crore($claim_amount)
    {
        $total_dispute = $claim_amount + $this->fixed_administrative_cost;
        $basic_amount = 10000000; // 1 crore
        $excess_amount = $total_dispute - $basic_amount;

        $basic_administrative_cost = 95000; // Rs. 95000

        // Additional Arbitrator’s Fees @0.25% of Excess Amount
        $excess_amount_percent = 0.25;
        $aaf_excess_amount_percent = ($excess_amount * $excess_amount_percent) / 100;

        // Total Arbitrator’s Fees
        $total_administrative_cost = $basic_administrative_cost + $aaf_excess_amount_percent;

        // Each party share 
        $each_party_share = $total_administrative_cost / 2;

        // Each party payable 
        $each_party_payable = $each_party_share;

        return [
            'claim_amount_rupee' => $claim_amount,
            'total_administrative_cost' => $total_administrative_cost,
            'total_administrative_cost_dollar' => rupee_to_dollar($total_administrative_cost),
            'each_party_share' => $each_party_share,
            'each_party_share_dollar' => rupee_to_dollar($each_party_share),
            'each_party_payable' => $each_party_payable,
            'each_party_payable_dollar' => rupee_to_dollar($each_party_payable),

            'basic_amount' => $basic_amount,
            'excess_amount' => $excess_amount,
            'basic_administrative_cost' => $basic_administrative_cost,
            'excess_amount_percent' => $excess_amount_percent,
            'aaf_excess_amount_percent' => $aaf_excess_amount_percent,
            'total_administrative_cost' => $total_administrative_cost,
        ];
    }


    // ===========================================================================
    // Function for above 10 Crore
    function int_arb_arb_calc_above_10_crore($claim_amount)
    {
        $total_dispute = $claim_amount + $this->fixed_administrative_cost;
        $basic_amount = 100000000; // 10 crore
        $excess_amount = $total_dispute - $basic_amount;

        $basic_administrative_cost = 320000; // Rs. 320000

        // Additional Arbitrator’s Fees @0.15% of Excess Amount
        $excess_amount_percent = 0.15;
        $aaf_excess_amount_percent = ($excess_amount * $excess_amount_percent) / 100;

        // Total Arbitrator’s Fees
        $total_administrative_cost = $basic_administrative_cost + $aaf_excess_amount_percent;

        // Each party share 
        $each_party_share = $total_administrative_cost / 2;

        // Each party payable 
        $each_party_payable = $each_party_share;

        return [
            'claim_amount_rupee' => $claim_amount,
            'total_administrative_cost' => $total_administrative_cost,
            'total_administrative_cost_dollar' => rupee_to_dollar($total_administrative_cost),
            'each_party_share' => $each_party_share,
            'each_party_share_dollar' => rupee_to_dollar($each_party_share),
            'each_party_payable' => $each_party_payable,
            'each_party_payable_dollar' => rupee_to_dollar($each_party_payable),

            'basic_amount' => $basic_amount,
            'excess_amount' => $excess_amount,
            'basic_administrative_cost' => $basic_administrative_cost,
            'excess_amount_percent' => $excess_amount_percent,
            'aaf_excess_amount_percent' => $aaf_excess_amount_percent,
            'total_administrative_cost' => $total_administrative_cost,
        ];
    }

    // Internationl Administrative Charges Calculation END ==================
}
