<?php

namespace User\FinReport\Model;

use Exception;

class FinReports extends \PDO
{
    public const DSN = "sqlite:file:../db/db.sqlite";
    function __construct()
    {
        parent::__construct(self::DSN);
    }

    function getUserData()
    {


        //var_dump($users); die();
        //$users = $this->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();
        // @TODO "В выпадающем списке должны отображаться только те пользователи у которых имеются транзакции"

        // @todo добавить проверку

        $users = $this->query("SELECT * FROM users  WHERE id IN (
        SELECT user_id from user_accounts ua WHERE 
        id in (SELECT DISTINCT account_to FROM transactions)
           OR 
        id in (SELECT DISTINCT account_from FROM transactions)
        )")->fetchAll();

        //var_dump($users);

        return $users;

    }

    public function getUserMonthBalance($userId): false|array
    {
//        $sql_income = "WITH froms AS (
//    SELECT sum(amount) AS SUM_FROM, t.account_from as FROM_ACC, t.account_to, strftime('%m', t.trdate) as Month_FROM
//FROM transactions t
//WHERE t.account_from IN (SELECT id from user_accounts ua WHERE ua.user_id=:user_id)
//group by FROM_ACC, Month_FROM
//)
//SELECT sum(amount) AS SUM_TO, COALESCE(SUM_FROM, 0), (sum(amount) - COALESCE(SUM_FROM, 0)) AS balance, t.account_to TO_ACC, strftime('%m', t.trdate) as Month_TO
//FROM transactions t
//FULL JOIN froms AS froms ON MONTH_TO = Month_FROM AND FROM_ACC = t.account_to
//WHERE t.account_to IN (SELECT id from user_accounts ua WHERE ua.user_id=:user_id)
//group BY TO_ACC, MONTH_TO";

        $sql_income="WITH froms AS (
    SELECT sum(amount) AS SUM_FROM, t.account_from as FROM_ACC, t.account_to, strftime('%m', t.trdate) as Month_FROM
FROM transactions t
WHERE t.account_from IN (SELECT id from user_accounts ua WHERE ua.user_id=:user_id)
group by FROM_ACC, Month_FROM 
)
SELECT (sum(amount) - COALESCE(SUM_FROM, 0)) AS balance, t.account_to account, strftime('%m', t.trdate) as month
FROM transactions t
FULL JOIN froms AS froms ON month = Month_FROM AND FROM_ACC = t.account_to
WHERE t.account_to IN (SELECT id from user_accounts ua WHERE ua.user_id=:user_id) 
group BY t.account_to, month";

        $sth = $this->prepare($sql_income);
        $sth->execute([":user_id" => $userId]);

        $data = $sth->fetchAll(\PDO::FETCH_ASSOC);

        //echo '-----------------------------------------------INCOME--<br>';
        //echo '<pre>'; print_r($data); echo '</pre>'; //die();


        return $data;
    }

}