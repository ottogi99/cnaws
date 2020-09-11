<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGetPerformanceOperatingProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getpdo()->exec("
          	CREATE PROCEDURE GetPerformanceOperating(
          		IN p_business_year INT,
          		IN p_sigun_code VARCHAR(255),
          		IN p_nonghyup_id VARCHAR(255)
          	)
          	BEGIN
          		SELECT
                p_business_year as business_year,
          			siguns.name sigun_name,
          			T1.name nonghyup_name,
          			T2.small_farmer_number small_farmer_number,
          			T3.machine_supporter_number machine_supporter_number,
          			T4.large_farmer_number large_farmer_number,
          			T5.manpower_supporter_number manpower_supporter_number,
          			T6.machine_supporter_working_area machine_supporter_working_area,
          			T7.manpower_supporter_working_days manpower_supporter_working_days
          		FROM
          		users T1
              LEFT OUTER JOIN
          		(
          			SELECT * FROM activated_users
          			WHERE `business_year` = IF(p_business_year = '', `business_year`, p_business_year)
          		) T8
          		ON T1.nonghyup_id = T8.nonghyup_id
          		LEFT OUTER JOIN siguns
          		ON T1.sigun_code = siguns.code
          		LEFT OUTER JOIN
          		(
          			SELECT *
          			FROM
          			(
          				SELECT small_farmers.nonghyup_id, COUNT(small_farmers.nonghyup_id) AS small_farmer_number
          				FROM small_farmers
                  WHERE `business_year` = IF(p_business_year = '', `business_year`, p_business_year)
          				GROUP BY small_farmers.nonghyup_id
          			) T
          		) T2
          		ON T1.nonghyup_id = T2.nonghyup_id
          		LEFT OUTER JOIN
          		(
          			SELECT *
          			FROM
          			(
          				SELECT machine_supporters.nonghyup_id, COUNT(machine_supporters.nonghyup_id) AS machine_supporter_number
          				FROM machine_supporters
                  WHERE `business_year` = IF(p_business_year = '', `business_year`, p_business_year)
          				GROUP BY machine_supporters.nonghyup_id
          			) T
          		) T3
          		ON T1.nonghyup_id = T3.nonghyup_id
          		LEFT OUTER JOIN
          		(
          			SELECT *
          			FROM
          			(
          				SELECT large_farmers.nonghyup_id, COUNT(large_farmers.nonghyup_id) AS large_farmer_number
          				FROM large_farmers
                  WHERE `business_year` = IF(p_business_year = '', `business_year`, p_business_year)
          				GROUP BY large_farmers.nonghyup_id
          			) T
          		) T4
          		ON T1.nonghyup_id = T4.nonghyup_id
          		LEFT OUTER JOIN
          		(
          			SELECT *
          			FROM
          			(
          				SELECT manpower_supporters.nonghyup_id, COUNT(manpower_supporters.nonghyup_id) AS manpower_supporter_number
          				FROM manpower_supporters
                  WHERE `business_year` = IF(p_business_year = '', `business_year`, p_business_year)
          				GROUP BY manpower_supporters.nonghyup_id
          			) T
          		) T5
          		ON T1.nonghyup_id = T5.nonghyup_id
          		LEFT OUTER JOIN
          		(
          			SELECT *
          			FROM
          			(
          				SELECT status_machine_supporters.nonghyup_id, SUM(status_machine_supporters.working_area) AS machine_supporter_working_area
          				FROM status_machine_supporters
          			  WHERE `business_year` = IF(p_business_year = '', `business_year`, p_business_year)
          				GROUP BY status_machine_supporters.nonghyup_id
          			) T
          		) T6
          		ON T1.nonghyup_id = T6.nonghyup_id
          		LEFT OUTER JOIN
          		(
          			SELECT *
          			FROM
          			(
          				SELECT status_manpower_supporters.nonghyup_id, SUM(status_manpower_supporters.working_days) AS manpower_supporter_working_days
          				FROM status_manpower_supporters
          			  WHERE `business_year` = IF(p_business_year = '', `business_year`, p_business_year)
          				GROUP BY status_manpower_supporters.nonghyup_id
          			) T
          		) T7
          		ON T1.nonghyup_id = T7.nonghyup_id
              WHERE siguns.code = IF(p_sigun_code = '', siguns.code, p_sigun_code)
              AND T1.nonghyup_id = IF(p_nonghyup_id = '', T1.nonghyup_id, p_nonghyup_id)
              AND T1.is_admin != 1
          		ORDER BY siguns.sequence, T1.sequence;
          	END
        ");
    }

    public function down()
    {
        DB::connection()->getpdo()->exec('DROP PROCEDURE IF EXISTS `GetPerformanceOperating`');
        // DB::statement('DROP PROCEDURE IF EXISTS `GetPerformanceOperating`');
    }
}
