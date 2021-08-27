<?php

namespace Prokl\WebProfilierBundle\Bitrix\Metric;

use Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Check;
use mysqli;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

/**
 * Class UnsentMailEventsDataCollector
 *
 * @since 27.08.2021
 */
class MetrixDataCollector extends DataCollector
{
    /**
     * @var $mysqli
     */
    private $mysqli;

    /**
     * @var array
     */
    private $checkers;

    public function __construct()
    {
        $this->checkers = (new BitrixCheckerBag())->getCheckers();
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Throwable $exception = null)
    {
        $sqlUnsentMail = <<<END
select count(*) as CNT from b_event where SUCCESS_EXEC = 'N' or DATE_EXEC is null
END;

        $sqlLoginsUsers = <<<END
select count(*) as CNT from b_user where LAST_LOGIN >= '%s'
END;


        $this->data = [
            'unsentmail' => $this->calculateSimpleSqlMetric($sqlUnsentMail, 'CNT'),
            'loginsusers' => $this->calculateSimpleSqlMetric($sqlLoginsUsers, 'CNT'),
        ];

        /** @var Check $checker */
        foreach ($this->checkers as $checker) {
            $result = $checker->run();
            $this->data['checkers'][] = [
              'result' =>  $result ? 'OK' : 'Failed',
              'title' =>  $checker->name(),
              'messages' => array_unique($checker->getMessages())
            ];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'metrics';
    }

    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        $this->data = [];
    }

    /**
     * @return string
     */
    public function getUnsentMail()
    {
        return $this->data['unsentmail'];
    }

    /**
     * @return string
     */
    public function getLoginsUsers()
    {
        return $this->data['loginsusers'];
    }

    /**
     * @return array
     */
    public function getCheckers()
    {
        return $this->data['checkers'];
    }

    /**
     * @param string $query
     * @param string $colName
     *
     * @return mixed
     */
    private function calculateSimpleSqlMetric(string $query, string $colName)
    {
        $this->mysqli = new mysqli(
            $_ENV['DB_HOST'],
            $_ENV['DB_LOGIN'],
            $_ENV['DB_PASSWORD'],
            $_ENV['DB_NAME'],
            3306
        );

        $result = $this->mysqli
            ->query($query);
        if (false === $result) {
            throw new RuntimeException(
                sprintf(
                    'Error executing query (%s): %s',
                    $this->mysqli->errno,
                    $this->mysqli->error
                )
            );
        }
        $row = $result->fetch_assoc();
        $result->free();

        if (!array_key_exists($colName, $row)) {
            throw new RuntimeException(
                sprintf(
                    'Column `%s` is not found in the query result for metric %s',
                    $colName,
                    static::class
                )
            );
        }

        return $row[$colName];
    }
}