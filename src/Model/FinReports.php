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

        $users = $this->query(
          "SELECT * FROM users  WHERE id IN (
        SELECT user_id from user_accounts ua WHERE 
        id in (SELECT DISTINCT account_to FROM transactions)
           OR 
        id in (SELECT DISTINCT account_from FROM transactions)
        )"
        )->fetchAll();

        //var_dump($users);

        return $users;
    }

    public function getUserMonthBalance($userId): false|array
    {
        $sql_income = "
        WITH selected_user_accounts AS (SELECT id from user_accounts ua WHERE ua.user_id=:user_id),
        
        from_transactions AS (
    SELECT sum(amount) AS summary_from, account_from as from_account, account_to, strftime('%m', trdate) AS month_from
    FROM transactions
    WHERE account_from IN selected_user_accounts
    GROUP BY from_account, month_from),
    
    to_transactions AS (
    SELECT sum(amount) AS summary_to, account_to as to_account, account_from, strftime('%m', trdate) AS month_to
    FROM transactions
	  WHERE account_to IN selected_user_accounts
    GROUP BY to_account, month_to)

    SELECT COALESCE(from_account, tt.to_account) AS account,
    COALESCE(tt.month_to, month_from) AS month,  
    COALESCE(tt.summary_to, 0) - COALESCE(summary_from, 0) AS balance  
    FROM from_transactions ft
    
    FULL JOIN to_transactions tt ON month = month_from AND from_account = tt.to_account
    ORDER BY account, month 
    ";

        $sth = $this->prepare($sql_income);
        $sth->execute([":user_id" => $userId]);

        $data = $sth->fetchAll(\PDO::FETCH_ASSOC);

        return $data;
    }

}