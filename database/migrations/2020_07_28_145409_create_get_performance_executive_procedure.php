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
            		'합계' sigun_name,
            		'합계' nonghyup_name,
            		IFNULL(T1.budget_sum, 0) as budget_sum,
            		IFNULL(T1.budget_do, 0) as budget_do,
            		IFNULL(T1.budget_sigun, 0) as budget_sigun,
            		IFNULL(T1.budget_center, 0) as budget_center,
            		IFNULL(T1.budget_unit, 0) as budget_unit,
            		IFNULL(T2.payment_sum, 0) as payment_sum,
            		IFNULL(T2.payment_do, 0) as payment_do,
            		IFNULL(T2.payment_sigun, 0) payment_sigun,
            		IFNULL(T2.payment_center, 0) payment_center,
            		IFNULL(T2.payment_unit, 0) payment_unit,
            		IFNULL(T1.budget_sum, 0) - IFNULL(T2.payment_sum, 0) as balance_sum,
            		IFNULL(T1.budget_do, 0) - IFNULL(T2.payment_do, 0) as balance_do,
            		IFNULL(T1.budget_sigun, 0) - IFNULL(T2.payment_sigun, 0) as balance_sigun,
            		IFNULL(T1.budget_center, 0) - IFNULL(T2.payment_center, 0) as balance_center,
            		IFNULL(T1.budget_unit, 0) - IFNULL(T2.payment_unit, 0) as balance_unit,
            		ROUND(IFNULL(T2.payment_sum, 0) / IFNULL(T1.budget_sum, 0) * 100, 1) as execution_rate
            	FROM
            	(
            		SELECT SUM(amount) AS budget_sum,
            		TRUNCATE(sum(amount) * 0.21, 0) + (sum(amount) - (TRUNCATE(sum(amount) * 0.21, 0) + TRUNCATE(sum(amount) * 0.49, 0) + TRUNCATE(sum(amount) * 0.20, 0) + TRUNCATE(sum(amount) * 0.10, 0))) AS budget_do,
            		TRUNCATE(sum(amount) * 0.49, 0) AS budget_sigun,
            		TRUNCATE(sum(amount) * 0.20, 0) AS budget_center,
            		TRUNCATE(sum(amount) * 0.10, 0) AS budget_unit
            		FROM budgets
            		WHERE `business_year` = IF(p_business_year = '', `business_year`, p_business_year)
            		AND sigun_code = IF(p_sigun_code = '', sigun_code, p_sigun_code)
            		AND nonghyup_id = IF(p_nonghyup_id = '', nonghyup_id, p_nonghyup_id)
            	) T1,
            	(
            		SELECT
            			IFNULL(T1.payment_sum, 0)+IFNULL(T2.payment_sum, 0)+IFNULL(T3.payment_sum, 0)+IFNULL(T4.payment_sum, 0)+IFNULL(T5.payment_sum, 0) AS payment_sum,
            			IFNULL(T1.payment_do, 0)+IFNULL(T2.payment_do, 0)+IFNULL(T3.payment_do, 0)+IFNULL(T4.payment_do, 0)+IFNULL(T5.payment_do, 0) AS payment_do,
            			IFNULL(T1.payment_sigun, 0)+IFNULL(T2.payment_sigun, 0)+IFNULL(T3.payment_sigun, 0)+IFNULL(T4.payment_sigun, 0)+IFNULL(T5.payment_sigun, 0) AS payment_sigun,
            			IFNULL(T1.payment_center, 0)+IFNULL(T2.payment_center, 0)+IFNULL(T3.payment_center, 0)+IFNULL(T4.payment_center, 0)+IFNULL(T5.payment_center, 0) AS payment_center,
            			IFNULL(T1.payment_unit, 0)+IFNULL(T2.payment_unit, 0)+IFNULL(T3.payment_unit, 0)+IFNULL(T4.payment_unit, 0)+IFNULL(T5.payment_unit, 0) AS payment_unit
            		FROM
            		(
            			SELECT
            				SUM(payment_sum) AS payment_sum,
            				SUM(payment_do) AS payment_do,
            				SUM(payment_sigun) AS payment_sigun,
            				SUM(payment_center) AS payment_center,
            				SUM(payment_unit) AS payment_unit
            			FROM status_education_promotions
            			WHERE `business_year` = IF(p_business_year = '', `business_year`, p_business_year)
            			AND sigun_code = IF(p_sigun_code = '', sigun_code, p_sigun_code)
            			AND nonghyup_id = IF(p_nonghyup_id = '', nonghyup_id, p_nonghyup_id)
            		) T1,
            		(
            			SELECT
            				SUM(payment_sum) AS payment_sum,
            				SUM(payment_do) AS payment_do,
            				SUM(payment_sigun) AS payment_sigun,
            				SUM(payment_center) AS payment_center,
            				SUM(payment_unit) AS payment_unit
            			FROM status_machine_supporters
            			WHERE `business_year` = IF(p_business_year = '', `business_year`, p_business_year)
            			AND sigun_code = IF(p_sigun_code = '', sigun_code, p_sigun_code)
            			AND nonghyup_id = IF(p_nonghyup_id = '', nonghyup_id, p_nonghyup_id)
            		) T2,
            		(
            			SELECT
            				SUM(payment_sum) AS payment_sum,
            				SUM(payment_do) AS payment_do,
            				SUM(payment_sigun) AS payment_sigun,
            				SUM(payment_center) AS payment_center,
            				SUM(payment_unit) AS payment_unit
            			FROM status_manpower_supporters
            			WHERE `business_year` = IF(p_business_year = '', `business_year`, p_business_year)
            			AND sigun_code = IF(p_sigun_code = '', sigun_code, p_sigun_code)
            			AND nonghyup_id = IF(p_nonghyup_id = '', nonghyup_id, p_nonghyup_id)
            		) T3,
            		(
            			SELECT
            				SUM(payment_sum) AS payment_sum,
            				SUM(payment_do) AS payment_do,
            				SUM(payment_sigun) AS payment_sigun,
            				SUM(payment_center) AS payment_center,
            				SUM(payment_unit) AS payment_unit
            			FROM status_labor_payments
            			WHERE `business_year` = IF(p_business_year = '', `business_year`, p_business_year)
            			AND sigun_code = IF(p_sigun_code = '', sigun_code, p_sigun_code)
            			AND nonghyup_id = IF(p_nonghyup_id = '', nonghyup_id, p_nonghyup_id)
            		) T4,
            		(
            			SELECT
            				SUM(payment_sum) AS payment_sum,
            				SUM(payment_do) AS payment_do,
            				SUM(payment_sigun) AS payment_sigun,
            				SUM(payment_center) AS payment_center,
            				SUM(payment_unit) AS payment_unit
            			FROM status_operating_costs
            			WHERE `business_year` = IF(p_business_year = '', `business_year`, p_business_year)
            			AND sigun_code = IF(p_sigun_code = '', sigun_code, p_sigun_code)
            			AND nonghyup_id = IF(p_nonghyup_id = '', nonghyup_id, p_nonghyup_id)
            		) T5
            	) T2
            	UNION ALL
            	(
            		SELECT
            			p_business_year as business_year,
            			T2.name AS sigun_name,
            			T1.name nonghyup_name,
            			IFNULL(T3.amount, 0) AS budget_sum,
            			IFNULL(TRUNCATE(T3.amount * 0.21, 0) + (T3.amount - (TRUNCATE(T3.amount * 0.21, 0) + TRUNCATE(T3.amount * 0.49, 0) + TRUNCATE(T3.amount * 0.20, 0) + TRUNCATE(T3.amount * 0.10, 0))), 0) AS budget_do,
            			IFNULL(TRUNCATE(T3.amount * 0.49, 0), 0) AS budget_sigun,
            			IFNULL(TRUNCATE(T3.amount * 0.20, 0), 0) AS budget_center,
            			IFNULL(TRUNCATE(T3.amount * 0.10, 0), 0) AS budget_unit,
            			IFNULL(T4.payment_sum, 0) AS payment_sum,
            			IFNULL(T4.payment_do, 0) AS payment_do,
            			IFNULL(T4.payment_sigun, 0) AS payment_sigun,
            			IFNULL(T4.payment_center, 0) AS payment_center,
            			IFNULL(T4.payment_unit, 0) AS payment_unit,
            			ROUND(IFNULL(T3.amount, 0) - T4.payment_sum) AS balance_sum,
            			ROUND(TRUNCATE(IFNULL(T3.amount, 0) * 0.21, 0) + (IFNULL(T3.amount, 0) - (TRUNCATE(IFNULL(T3.amount, 0) * 0.21, 0) + TRUNCATE(IFNULL(T3.amount, 0) * 0.49, 0) + TRUNCATE(IFNULL(T3.amount, 0) * 0.20, 0) + TRUNCATE(IFNULL(T3.amount, 0) * 0.10, 0))) - T4.payment_do) AS balance_do,
            			ROUND(TRUNCATE(IFNULL(T3.amount, 0) * 0.49, 0) - T4.payment_sigun) AS balance_sigun,
            			ROUND(TRUNCATE(IFNULL(T3.amount, 0) * 0.20, 0) - T4.payment_center) AS balance_center,
            			ROUND(TRUNCATE(IFNULL(T3.amount, 0) * 0.10, 0) - T4.payment_unit) AS balance_unit,
            			ROUND(T4.payment_sum / IFNULL(T3.amount, 0) * 100, 1) AS execution_rate
            		FROM users T1
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
            			T1.nonghyup_id,
            			IFNULL(T2.payment_sum, 0)+IFNULL(T3.payment_sum, 0)+IFNULL(T4.payment_sum, 0)+IFNULL(T5.payment_sum, 0)+IFNULL(T6.payment_sum, 0) AS payment_sum,
            			IFNULL(T2.payment_do, 0)+IFNULL(T3.payment_do, 0)+IFNULL(T4.payment_do, 0)+IFNULL(T5.payment_do, 0)+IFNULL(T6.payment_do, 0) AS payment_do,
            			IFNULL(T2.payment_sigun, 0)+IFNULL(T3.payment_sigun, 0)+IFNULL(T4.payment_sigun, 0)+IFNULL(T5.payment_sigun, 0)+IFNULL(T6.payment_sigun, 0) AS payment_sigun,
            			IFNULL(T2.payment_center, 0)+IFNULL(T3.payment_center, 0)+IFNULL(T4.payment_center, 0)+IFNULL(T5.payment_center, 0)+IFNULL(T6.payment_center, 0) AS payment_center,
            			IFNULL(T2.payment_unit, 0)+IFNULL(T3.payment_unit, 0)+IFNULL(T4.payment_unit, 0)+IFNULL(T5.payment_unit, 0)+IFNULL(T6.payment_unit, 0) AS payment_unit
            		FROM
            		users T1
            		LEFT OUTER JOIN	-- 교육.홍보비
            		(
            			SELECT
            				nonghyup_id,
            				SUM(payment_sum) AS payment_sum,
            				SUM(payment_do) AS payment_do,
            				SUM(payment_sigun) AS payment_sigun,
            				SUM(payment_center) AS payment_center,
            				SUM(payment_unit) AS payment_unit
            			FROM status_education_promotions
            			WHERE `business_year` = IF(p_business_year = '', `business_year`, p_business_year)
            			GROUP BY nonghyup_id
            		) T2
            		ON T1.nonghyup_id = T2.nonghyup_id
            		LEFT OUTER JOIN	-- 농기계지원반
            		(
            			SELECT
            				nonghyup_id,
            				SUM(payment_sum) AS payment_sum,
            				SUM(payment_do) AS payment_do,
            				SUM(payment_sigun) AS payment_sigun,
            				SUM(payment_center) AS payment_center,
            				SUM(payment_unit) AS payment_unit
            			FROM status_machine_supporters
            			WHERE `business_year` = IF(p_business_year = '', `business_year`, p_business_year)
            			GROUP BY nonghyup_id
            		) T3
            		ON T1.nonghyup_id = T3.nonghyup_id
            		LEFT OUTER JOIN	-- 인력지원반
            		(
            			SELECT
            				nonghyup_id,
            				SUM(payment_sum) AS payment_sum,
            				SUM(payment_do) AS payment_do,
            				SUM(payment_sigun) AS payment_sigun,
            				SUM(payment_center) AS payment_center,
            				SUM(payment_unit) AS payment_unit
            			FROM status_manpower_supporters
            			WHERE `business_year` = IF(p_business_year = '', `business_year`, p_business_year)
            			GROUP BY nonghyup_id
            		) T4
            		ON T1.nonghyup_id = T4.nonghyup_id
            		LEFT OUTER JOIN	-- 센터운영비(인건비)
            		(
            			SELECT
            				nonghyup_id,
            				SUM(payment_sum) AS payment_sum,
            				SUM(payment_do) AS payment_do,
            				SUM(payment_sigun) AS payment_sigun,
            				SUM(payment_center) AS payment_center,
            				SUM(payment_unit) AS payment_unit
            			FROM status_labor_payments
            			WHERE `business_year` = IF(p_business_year = '', `business_year`, p_business_year)
            			GROUP BY nonghyup_id
            		) T5
            		ON T1.nonghyup_id = T5.nonghyup_id
            		LEFT OUTER JOIN	-- 센터 운영비(운영비)
            		(
            			SELECT
            				nonghyup_id,
            				SUM(payment_sum) AS payment_sum,
            				SUM(payment_do) AS payment_do,
            				SUM(payment_sigun) AS payment_sigun,
            				SUM(payment_center) AS payment_center,
            				SUM(payment_unit) AS payment_unit
            			FROM status_operating_costs
            			WHERE `business_year` = IF(p_business_year = '', `business_year`, p_business_year)
            			GROUP BY nonghyup_id
            		) T6
            		ON T1.nonghyup_id = T6.nonghyup_id
            		) T4
            		ON T1.nonghyup_id = T4.nonghyup_id
            		WHERE T2.code = IF(p_sigun_code = '', T2.code, p_sigun_code)
            		AND T1.nonghyup_id = IF(p_nonghyup_id = '', T1.nonghyup_id, p_nonghyup_id)
            		AND T1.activated = 1
            		AND T1.is_admin != 1
            		ORDER BY T2.sequence, T1.sequence
            	);
          	END
        ");
    }

    public function down()
    {
        DB::connection()->getpdo()->exec('DROP PROCEDURE IF EXISTS `GetPerformanceExecutive`');
        // DB::statement('DROP PROCEDURE IF EXISTS `GetPerformanceExecutive`');
    }
}
