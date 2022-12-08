<?php

$salary = 0;
$budget = 0;
$expense = 0;
$result = 0;
$account_id = 0;

function getSummary($conn, $username) {
    echo '<div class="salary_budget">';
    $sql1 = "SELECT `SALARY_AMOUNT`, `SALARY_FREQ` FROM `SALARY` AS S"
            . " JOIN ACCOUNT_INFO AS A"
            . " ON S.ACCOUNT_ID = A.ACCOUNT_ID"
            . " WHERE `ACCOUNT_USERNAME` = :username"
            . " AND `SALARY_STARTDATE` = (SELECT MAX(`SALARY_STARTDATE`) FROM `SALARY` AS S JOIN ACCOUNT_INFO AS A ON S.ACCOUNT_ID = A.ACCOUNT_ID WHERE `ACCOUNT_USERNAME` = :username);";
    $query1 = $conn->prepare($sql1);
    $query1->bindValue(':username', $username);
    $query1->execute();
    if ($query1->rowCount() == 0) {
        $salary = 0;
    } else {
        $result1 = $query1->fetch(PDO::FETCH_ASSOC);
        switch ($result1['SALARY_FREQ']) {
            case 'Bi-Weekly':
                $salary = $result1['SALARY_AMOUNT'] * 2;
                break;
            case 'Yearly':
                $salary = round($result1['SALARY_AMOUNT'] / 12, 2);
                break;
            default:
                $salary = $result1['SALARY_AMOUNT'];
                break;
        }
    }
    echo '<div class="salary_info"> <h5>Monthly Income</h5>$ ' . $salary . '</div>';
    //Getting post values
    $sql1 = "SELECT `BUDGET_AMOUNT`, `BUDGET_FREQ`, `BUDGET_STARTDATE`, `BUDGET_ENDDATE` FROM `BUDGET` AS B"
            . " JOIN ACCOUNT_INFO AS A"
            . " ON B.ACCOUNT_ID = A.ACCOUNT_ID"
            . " WHERE `ACCOUNT_USERNAME` = :username"
            . " AND `BUDGET_STARTDATE` = (SELECT MAX(`BUDGET_STARTDATE`) FROM `BUDGET` AS B JOIN ACCOUNT_INFO AS A ON B.ACCOUNT_ID = A.ACCOUNT_ID WHERE `ACCOUNT_USERNAME` = :username);";
    $query1 = $conn->prepare($sql1);
    $query1->bindValue(':username', $username);
    $query1->execute();
    if ($query1->rowCount() == 0) {
        $budget = 0;
    } else {
        $result1 = $query1->fetch(PDO::FETCH_ASSOC);
        switch ($result1['BUDGET_FREQ']) {
            case 'Weekly':
                $budget = $result1['BUDGET_AMOUNT'] * 4;
                break;
            case 'Yearly':
                $budget = round($result1['BUDGET_AMOUNT'] / 12, 2);
                break;
            default:
                $budget = $result1['BUDGET_AMOUNT'];
                break;
        }
        echo "<h5>Period: </h5>" . $result1['BUDGET_STARTDATE'] . " " . $result1['BUDGET_ENDDATE'];
    }
    echo '<div class="budget_info"> <h5>Monthly Budget</h5>$ ' . $budget . '</div>';
    //Get Transaction
    $sql1 = "SELECT TRANSACTION_AMOUNT FROM `USER_TRANSACTION` AS U"
            . " JOIN CATEGORY AS C"
            . " ON U.CATEGORY_ID = C.CATEGORY_ID"
            . " JOIN ACCOUNT_INFO AS A"
            . " ON C.ACCOUNT_ID = A.ACCOUNT_ID"
            . " WHERE `ACCOUNT_USERNAME` = :username";
    $query1 = $conn->prepare($sql1);
    $query1->bindValue(':username', $username);
    $query1->execute();
    if ($query1->rowCount() == 0) {
        $expense = 0;
        echo '<div class="expense_info"> <h5>Monthly Expense</h5>$ ' . $expense . '</div>';
        $result = $budget - $expense;
        echo '<div class="expense_info"> <h5>Spendable Amount</h5>$ ' . $result . '</div>';
    } else {
        $sql1 = "SELECT SUM(TRANSACTION_AMOUNT) AS TOTAL, `TRANSACTION_TYPE` FROM `USER_TRANSACTION` AS U"
                . " JOIN CATEGORY AS C"
                . " ON U.CATEGORY_ID = C.CATEGORY_ID"
                . " JOIN ACCOUNT_INFO AS A"
                . " ON C.ACCOUNT_ID = A.ACCOUNT_ID"
                . " WHERE `ACCOUNT_USERNAME` = :username"
                . " AND TRANSACTION_DATE BETWEEN :start_date AND :end_date"
                . " GROUP BY `TRANSACTION_TYPE`";
        $query1 = $conn->prepare($sql1);
        $query1->bindValue(':username', $username);
        $query1->bindValue(':start_date', $result1['BUDGET_STARTDATE']);
        $query1->bindValue(':end_date', $result1['BUDGET_ENDDATE']);
        $query1->execute();
        $result1 = $query1->fetchAll(PDO::FETCH_ASSOC);
        if (count($result1) == 1 && $result1[0]['TRANSACTION_TYPE'] == 'Credit') {
            $expense = -$result1[0]['TOTAL'];
        } else if (count($result1) == 1 && $result1[0]['TRANSACTION_TYPE'] == 'Debit') {
            $expense = $result1[0]['TOTAL'];
        } else {
            $expense = -$result1[0]['TOTAL'] + $result1[1]['TOTAL'];
        }
        echo '<div class="expense_info"> <h5>Monthly Expense</h5>$ ' . $expense . '</div>';
        $result = $budget - $expense;
        echo '<div class="expense_info"> <h5>Spendable Amount</h5>$ ' . $result . '</div>';
    }
    echo '</div>';
    //Get Category
}

?>
