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
              	'합계' sigun_name,
              	'합계' nonghyup_name,
              	IFNULL(T1.small_farmer_number, 0) small_farmer_number,
              	IFNULL(T2.machine_supporter_number, 0) machine_supporter_number,
              	IFNULL(T3.large_farmer_number, 0) large_farmer_number,
              	IFNULL(T4.manpower_supporter_number, 0) manpower_supporter_number,
              	IFNULL(T5.machine_supporter_working_area, 0) machine_supporter_working_area,
              	IFNULL(T6.manpower_supporter_working_days, 0) manpower_supporter_working_days,
              	IFNULL(T7.machine_supporter_performance_days, 0) machine_supporter_performance_days,
              	IFNULL(T8.manpower_supporter_performance_days, 0) manpower_supporter_performance_days,
              	IFNULL(T9.machine_supporter_supported_farmers, 0) machine_supporter_supported_farmers,
              	IFNULL(T10.manpower_supporter_supported_farmers, 0) manpower_supporter_supported_farmers
              FROM
              (
              	  SELECT COUNT(small_farmers.nonghyup_id) AS small_farmer_number
              	  FROM small_farmers
              	  WHERE `business_year` = IF(p_business_year = '', `business_year`, p_business_year)
                  AND sigun_code = IF(p_sigun_code = '', sigun_code, p_sigun_code)
                  AND nonghyup_id = IF(p_nonghyup_id = '', nonghyup_id, p_nonghyup_id)
              ) T1,
              (
              	  SELECT COUNT(machine_supporters.nonghyup_id) AS machine_supporter_number
              	  FROM machine_supporters
              	  WHERE `business_year` = IF(p_business_year = '', `business_year`, p_business_year)
                  AND sigun_code = IF(p_sigun_code = '', sigun_code, p_sigun_code)
                  AND nonghyup_id = IF(p_nonghyup_id = '', nonghyup_id, p_nonghyup_id)
              ) T2,
              (
              	  SELECT COUNT(large_farmers.nonghyup_id) AS large_farmer_number
              	  FROM large_farmers
              	  WHERE `business_year` = IF(p_business_year = '', `business_year`, p_business_year)
                  AND sigun_code = IF(p_sigun_code = '', sigun_code, p_sigun_code)
                  AND nonghyup_id = IF(p_nonghyup_id = '', nonghyup_id, p_nonghyup_id)
              ) T3,
              (
              	  SELECT COUNT(manpower_supporters.nonghyup_id) AS manpower_supporter_number
              	  FROM manpower_supporters
              	  WHERE `business_year` = IF(p_business_year = '', `business_year`, p_business_year)
                  AND sigun_code = IF(p_sigun_code = '', sigun_code, p_sigun_code)
                  AND nonghyup_id = IF(p_nonghyup_id = '', nonghyup_id, p_nonghyup_id)
              ) T4,
              (
              	  SELECT SUM(status_machine_supporters.working_area) AS machine_supporter_working_area
              	  FROM status_machine_supporters
              	  WHERE `business_year` = IF(p_business_year = '', `business_year`, p_business_year)
                  AND sigun_code = IF(p_sigun_code = '', sigun_code, p_sigun_code)
                  AND nonghyup_id = IF(p_nonghyup_id = '', nonghyup_id, p_nonghyup_id)
              ) T5,
              (
              	  SELECT SUM(status_manpower_supporters.working_days) AS manpower_supporter_working_days
              	  FROM status_manpower_supporters
              	  WHERE `business_year` = IF(p_business_year = '', `business_year`, p_business_year)
                  AND sigun_code = IF(p_sigun_code = '', sigun_code, p_sigun_code)
                  AND nonghyup_id = IF(p_nonghyup_id = '', nonghyup_id, p_nonghyup_id)
              ) T6,
              (
              	SELECT SUM(T1.days) machine_supporter_performance_days
              	FROM
              	(
              		SELECT
              			T1.nonghyup_id,
              			IFNULL(SUM(T2.days), 0) days
              		FROM small_farmers T1
              		JOIN
              		(
              			WITH RECURSIVE CTE AS
              			(
              				SELECT nonghyup_id, farmer_id, job_start_date AS sdate, job_end_date AS edate
              				FROM status_machine_supporters
              				WHERE business_year = IF(p_business_year = '', `business_year`, p_business_year)
                      AND sigun_code = IF(p_sigun_code = '', sigun_code, p_sigun_code)
                      AND nonghyup_id = IF(p_nonghyup_id = '', nonghyup_id, p_nonghyup_id)
              				UNION ALL
              				select nonghyup_id, farmer_id, (sdate + INTERVAL 1 DAY), edate from CTE where sdate < edate
              			)
              			SELECT nonghyup_id, farmer_id, COUNT(DISTINCT(sdate)) AS days FROM CTE
              			GROUP BY nonghyup_id, farmer_id
              		) T2
              		ON T1.nonghyup_id = T2.nonghyup_id AND T1.id = T2.farmer_id
              		GROUP BY nonghyup_id
              	) T1
              ) T7,
              (
              	SELECT SUM(T1.days) manpower_supporter_performance_days
              	FROM
              	(
              		SELECT
              			T1.nonghyup_id,
              			IFNULL(SUM(T2.days), 0) days
              		FROM large_farmers T1
              		JOIN
              		(
              			WITH RECURSIVE CTE AS
              			(
              				SELECT nonghyup_id, farmer_id, job_start_date AS sdate, job_end_date AS edate
              				FROM status_manpower_supporters
              				WHERE business_year = IF(p_business_year = '', `business_year`, p_business_year)
                      AND sigun_code = IF(p_sigun_code = '', sigun_code, p_sigun_code)
                      AND nonghyup_id = IF(p_nonghyup_id = '', nonghyup_id, p_nonghyup_id)
              				UNION ALL
              				select nonghyup_id, farmer_id, (sdate + INTERVAL 1 DAY), edate from CTE where sdate < edate
              			)
              			SELECT nonghyup_id, farmer_id, COUNT(DISTINCT(sdate)) AS days FROM CTE
              			GROUP BY nonghyup_id, farmer_id
              		) T2
              		ON T1.nonghyup_id = T2.nonghyup_id AND T1.id = T2.farmer_id
              		GROUP BY nonghyup_id
              	) T1
              ) T8,
              (
              	SELECT SUM(T1.supported_farmers) machine_supporter_supported_farmers
              	FROM
              	(
              		SELECT T1.nonghyup_id, SUM(cnt) AS supported_farmers
              		FROM
              		(
              			SELECT nonghyup_id, farmer_id, 1 AS cnt
              			FROM status_machine_supporters
                    WHERE business_year = IF(p_business_year = '', `business_year`, p_business_year)
                    AND sigun_code = IF(p_sigun_code = '', sigun_code, p_sigun_code)
                    AND nonghyup_id = IF(p_nonghyup_id = '', nonghyup_id, p_nonghyup_id)
              			GROUP BY nonghyup_id, farmer_id
              		) T1
              		GROUP BY T1.nonghyup_id
              	) T1
              ) T9,
              (
              	SELECT SUM(T1.supported_farmers) manpower_supporter_supported_farmers
              	FROM
              	(
              		SELECT T1.nonghyup_id, SUM(cnt) AS supported_farmers
              		FROM
              		(
              			SELECT nonghyup_id, farmer_id, 1 AS cnt
              			FROM status_manpower_supporters
                    WHERE business_year = IF(p_business_year = '', `business_year`, p_business_year)
                    AND sigun_code = IF(p_sigun_code = '', sigun_code, p_sigun_code)
                    AND nonghyup_id = IF(p_nonghyup_id = '', nonghyup_id, p_nonghyup_id)
              			GROUP BY nonghyup_id, farmer_id
              		) T1
              		GROUP BY T1.nonghyup_id
              	) T1
              ) T10
              UNION ALL
              (
              SELECT
              	p_business_year as business_year,
              	siguns.name sigun_name,
              	T1.name nonghyup_name,
              	IFNULL(T3.small_farmer_number, 0) small_farmer_number,
              	IFNULL(T4.machine_supporter_number, 0) machine_supporter_number,
              	IFNULL(T5.large_farmer_number, 0) large_farmer_number,
              	IFNULL(T6.manpower_supporter_number, 0) manpower_supporter_number,
              	IFNULL(T7.machine_supporter_working_area, 0) machine_supporter_working_area,
              	IFNULL(T8.manpower_supporter_working_days, 0) manpower_supporter_working_days,
              	IFNULL(T9.days, 0) machine_supporter_performance_days,
              	IFNULL(T10.days, 0) manpower_supporter_performance_days,
              	IFNULL(T11.supported_farmers, 0) machine_supporter_supported_farmers,
              	IFNULL(T12.supported_farmers, 0) manpower_supporter_supported_farmers
              FROM
              users T1
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
              ) T3
              ON T1.nonghyup_id = T3.nonghyup_id
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
              ) T4
              ON T1.nonghyup_id = T4.nonghyup_id
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
              ) T5
              ON T1.nonghyup_id = T5.nonghyup_id
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
              ) T6
              ON T1.nonghyup_id = T6.nonghyup_id
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
              ) T7
              ON T1.nonghyup_id = T7.nonghyup_id
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
              ) T8
              ON T1.nonghyup_id = T8.nonghyup_id
              LEFT OUTER JOIN
              (
              	SELECT
              		T1.nonghyup_id,
              		IFNULL(SUM(T2.days), 0) days
              	FROM small_farmers T1
              	JOIN
              	(
              		WITH RECURSIVE CTE AS
              		(
              			SELECT nonghyup_id, farmer_id, job_start_date AS sdate, job_end_date AS edate
              			FROM status_machine_supporters
              			WHERE business_year = IF(p_business_year = '', `business_year`, p_business_year)
              			UNION ALL
              			select nonghyup_id, farmer_id, (sdate + INTERVAL 1 DAY), edate from CTE where sdate < edate
              		)
              		SELECT nonghyup_id, farmer_id, COUNT(DISTINCT(sdate)) AS days FROM CTE
              		GROUP BY nonghyup_id, farmer_id
              	) T2
              	ON T1.nonghyup_id = T2.nonghyup_id AND T1.id = T2.farmer_id
              	GROUP BY nonghyup_id
              ) T9
              ON T1.nonghyup_id = T9.nonghyup_id
              LEFT OUTER JOIN
              (
              	SELECT
              		T1.nonghyup_id,
              		IFNULL(SUM(T2.days), 0) days
              	FROM large_farmers T1
              	JOIN
              	(
              		WITH RECURSIVE CTE AS
              		(
              			SELECT nonghyup_id, farmer_id, job_start_date AS sdate, job_end_date AS edate
              			FROM status_manpower_supporters
              			WHERE business_year = IF(p_business_year = '', `business_year`, p_business_year)
              			UNION ALL
              			select nonghyup_id, farmer_id, (sdate + INTERVAL 1 DAY), edate from CTE where sdate < edate
              		)
              		SELECT nonghyup_id, farmer_id, COUNT(DISTINCT(sdate)) AS days FROM CTE
              		GROUP BY nonghyup_id, farmer_id
              	) T2
              	ON T1.nonghyup_id = T2.nonghyup_id AND T1.id = T2.farmer_id
              	GROUP BY nonghyup_id
              ) T10
              ON T1.nonghyup_id = T10.nonghyup_id
              LEFT OUTER JOIN
              (
              	SELECT T1.nonghyup_id, SUM(cnt) AS supported_farmers
              	FROM
              	(
              		SELECT nonghyup_id, farmer_id, 1 AS cnt
              		FROM status_machine_supporters
              		GROUP BY nonghyup_id, farmer_id
              	) T1
              	GROUP BY T1.nonghyup_id
              ) T11
              ON T1.nonghyup_id = T11.nonghyup_id
              LEFT OUTER JOIN
              (
              	SELECT T1.nonghyup_id, SUM(cnt) AS supported_farmers
              	FROM
              	(
              		SELECT nonghyup_id, farmer_id, 1 AS cnt
              		FROM status_manpower_supporters
              		GROUP BY nonghyup_id, farmer_id
              	) T1
              	GROUP BY T1.nonghyup_id
              ) T12
              ON T1.nonghyup_id = T12.nonghyup_id
              WHERE siguns.code = IF(p_sigun_code = '', siguns.code, p_sigun_code)
              AND T1.nonghyup_id = IF(p_nonghyup_id = '', T1.nonghyup_id, p_nonghyup_id)
              AND T1.is_admin != 1
              ORDER BY siguns.sequence, T1.sequence
              );
          	END
        ");
    }

    public function down()
    {
        DB::connection()->getpdo()->exec('DROP PROCEDURE IF EXISTS `GetPerformanceOperating`');
        // DB::statement('DROP PROCEDURE IF EXISTS `GetPerformanceOperating`');
    }
}
