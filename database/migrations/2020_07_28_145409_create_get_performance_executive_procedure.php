<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGetPerformanceExecutiveProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getpdo()->exec("
          	CREATE PROCEDURE GetPerformanceExecutive(
          		IN p_business_year INT,
          		IN p_sigun_code VARCHAR(255),
          		IN p_nonghyup_id VARCHAR(255)
          	)
          	BEGIN
          		SELECT
                p_business_year as business_year,
          			T2.name AS sigun_name,
          			T1.name nonghyup_name,
          			T3.amount AS budget_sum,
          			ROUND(T3.amount * 0.21) AS budget_do,
          			ROUND(T3.amount * 0.49) AS budget_sigun,
          			ROUND(T3.amount * 0.20) AS budget_center,
          			ROUND(T3.amount * 0.10) AS budget_unit,
          			T4.payment_sum AS payment_sum,
          			T4.payment_do AS payment_do,
          			T4.payment_sigun AS payment_sigun,
          			T4.payment_center AS payment_center,
          			T4.payment_unit AS payment_unit,
          			ROUND(T3.amount - T4.payment_sum) AS balance_sum,
          			ROUND(ROUND(T3.amount * 0.21) - T4.payment_do) AS balance_do,
          			ROUND(ROUND(T3.amount * 0.49) - T4.payment_sigun) AS balance_sigun,
          			ROUND(ROUND(T3.amount * 0.20) - T4.payment_center) AS balance_center,
                ROUND(ROUND(T3.amount * 0.10) - T4.payment_unit) AS balance_unit,
          			ROUND(T4.payment_sum / T3.amount, 1) AS execution_rate
          		FROM users T1
              LEFT OUTER JOIN
          		(
          			SELECT * FROM activated_users
          			WHERE `business_year` = IF(p_business_year = '', `business_year`, p_business_year)
          		) T5
          		ON T1.nonghyup_id = T5.nonghyup_id
          		LEFT OUTER JOIN siguns T2
          		ON T1.sigun_code = T2.code
          		LEFT OUTER JOIN
          		(
          			SELECT nonghyup_id, COUNT(nonghyup_id) AS NUMBER, SUM(amount) AS amount
          			FROM budgets
          			WHERE `business_year` = IF(p_business_year = '', `business_year`, p_business_year)
          			GROUP BY nonghyup_id
          		) T3
          		ON T1.nonghyup_id = T3.nonghyup_id
          		LEFT OUTER JOIN
          		(
          			SELECT
          				nonghyup_id,
          				COUNT(nonghyup_id) AS NUMBER,
          				SUM(payment_sum) AS payment_sum,
          				SUM(payment_do) AS payment_do,
          				SUM(payment_sigun) AS payment_sigun,
          				SUM(payment_center) AS payment_center,
          				SUM(payment_unit) AS payment_unit
          			FROM status_education_promotions
          			WHERE `business_year` = IF(p_business_year = '', `business_year`, p_business_year)
          			GROUP BY nonghyup_id
          		) T4
          		ON T1.nonghyup_id = T4.nonghyup_id
              WHERE T2.code = IF(p_sigun_code = '', T2.code, p_sigun_code)
              AND T1.nonghyup_id = IF(p_nonghyup_id = '', T1.nonghyup_id, p_nonghyup_id)
              AND T1.is_admin != 1
          		ORDER BY T2.sequence, T1.sequence;
          	END
        ");
    }

    public function down()
    {
        DB::connection()->getpdo()->exec('DROP PROCEDURE IF EXISTS `GetPerformanceExecutive`');
        // DB::statement('DROP PROCEDURE IF EXISTS `GetPerformanceExecutive`');
    }
}
