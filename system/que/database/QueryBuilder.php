<?php
/**
 * Created by PhpStorm.
 * User: Wisdom Emenike
 * Date: 9/30/2020
 * Time: 10:44 AM
 */

namespace que\database;


use Closure;
use Exception;
use JsonSerializable;
use que\common\exception\PreviousException;
use que\common\exception\QueRuntimeException;
use que\database\interfaces\Builder;
use que\database\interfaces\drivers\Driver;
use que\database\interfaces\drivers\DriverQueryBuilder;
use que\database\interfaces\drivers\DriverResponse;
use que\database\observer\Observer;
use que\database\observer\ObserverSignal;
use que\database\interfaces\model\Model;
use que\database\model\ModelCollection;
use que\http\HTTP;
use que\template\Pagination;
use que\template\Paginator;
use stdClass;

class QueryBuilder implements Builder
{
    /**
     * @var Driver
     */
    private Driver $driver;

    /**
     * @var DB
     */
    private DB $query;

    /**
     * @var DriverQueryBuilder
     */
    private DriverQueryBuilder $builder;

    /**
     * @var array
     */
    private array $pagination = [
        'status' => false,
        'pageName' => '',
        'perPage' => 0,
        'page' => 0,
        'tag' => ''
    ];

    /**
     * @var array
     */
    private static array $primaryKeys = [];

    /**
     * QueryBuilder constructor.
     * @param Driver $driver
     * @param DriverQueryBuilder $builder
     * @param DB $query
     */
    public function __construct(Driver $driver, DriverQueryBuilder $builder, DB $query)
    {
        $this->driver = $driver;
        $this->builder = $builder;
        $this->query = $query;
    }

    /**
     * @inheritDoc
     */
    public function table(string $table): Builder
    {
        // TODO: Implement table() method.
        $this->builder->setTable($table);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function columns($columns): Builder
    {
        // TODO: Implement columns() method.
        $this->builder->setColumns($columns);
        return $this;
    }

    public function select(...$columns): Builder
    {
        // TODO: Implement selectColumns() method.
        $this->builder->setSelect(...$columns);
        return $this;
    }


    /**
     * @inheritDoc
     */
    public function selectSub(Closure $callbackQuery, $as): Builder
    {
        // TODO: Implement selectSub() method.
        $this->builder->setSelectSub($callbackQuery, $as);
        return $this;
    }

    public function selectSubRaw($query, $as, array $bindings = null): Builder
    {
        // TODO: Implement selectSubRaw() method.
        $this->builder->setSelectSubRaw($query, $as, $bindings ?: []);
        return $this;
    }


    /**
     * @inheritDoc
     */
    public function selectJsonQuery($column, $alias, $path = null): Builder
    {
        // TODO: Implement selectJsonQuery() method.
        $this->builder->setSelectJsonQuery($column, $alias, $path);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function selectJsonValue($column, $alias, $path): Builder
    {
        // TODO: Implement selectJsonValue() method.
        $this->builder->setSelectJsonValue($column, $alias, $path);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function distinct(): Builder
    {
        // TODO: Implement distinct() method.
        $this->builder->setDistinct();
        return $this;
    }

    /**
     * @param $value
     * @param Closure $callback
     * @param Closure|null $default
     * @return Builder
     */
    public function when($value, Closure $callback, Closure $default = null): Builder
    {
        // TODO: Implement when() method.
        if ($value) {
            return $callback($this, $value) ?: $this;
        } elseif ($default) {
            return $default($this, $value) ?: $this;
        }

        return $this;
    }

    public function whenNot($value, Closure $callback, Closure $default = null): Builder
    {
        // TODO: Implement whenNot() method.
        if ($value) {
            return $callback($this, $value) ?: $this;
        } elseif ($default) {
            return $default($this, $value) ?: $this;
        }

        return $this;
    }


    /**
     * @inheritDoc
     */
    public function where($column, $value, $operator = null): Builder
    {
        // TODO: Implement where() method.
        $this->builder->setWhere($column, $value, $operator);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhere($column, $value, $operator = null): Builder
    {
        // TODO: Implement orWhere() method.
        $this->builder->setOrWhere($column, $value, $operator);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereIsNull($column): Builder
    {
        // TODO: Implement whereIsNull() method.
        $this->builder->setWhereIsNull($column);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereIsNull($column): Builder
    {
        // TODO: Implement orWhereIsNull() method.
        $this->builder->setOrWhereIsNull($column);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereIsNotNull($column): Builder
    {
        // TODO: Implement whereIsNotNull() method.
        $this->builder->setWhereIsNotNull($column);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereIsNotNull($column): Builder
    {
        // TODO: Implement orWhereIsNotNull() method.
        $this->builder->setOrWhereIsNotNull($column);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereIn($column, $values): Builder
    {
        // TODO: Implement whereIn() method.
        $this->builder->setWhereIn($column, $values);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereIn($column, $values): Builder
    {
        // TODO: Implement orWhereIn() method.
        $this->builder->setOrWhereIn($column, $values);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereNotIn($column, $values): Builder
    {
        // TODO: Implement whereNotIn() method.
        $this->builder->setWhereNotIn($column, $values);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereNotIn($column, $values): Builder
    {
        // TODO: Implement orWhereNotIn() method.
        $this->builder->setOrWhereNotIn($column, $values);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereBetween($column, $value1, $value2): Builder
    {
        // TODO: Implement whereBetween() method.
        $this->builder->setWhereBetween($column, $value1, $value2);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereBetween($column, $value1, $value2): Builder
    {
        // TODO: Implement orWhereBetween() method.
        $this->builder->setOrWhereBetween($column, $value1, $value2);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereNotBetween($column, $value1, $value2): Builder
    {
        // TODO: Implement whereNotBetween() method.
        $this->builder->setWhereNotBetween($column, $value1, $value2);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereNotBetween($column, $value1, $value2): Builder
    {
        // TODO: Implement orWhereNotBetween() method.
        $this->builder->setOrWhereNotBetween($column, $value1, $value2);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereIsJson($column): Builder
    {
        // TODO: Implement whereIsJson() method.
        $this->builder->setWhereIsJson($column);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereIsJson($column): Builder
    {
        // TODO: Implement orWhereIsJson() method.
        $this->builder->setOrWhereIsJson($column);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereIsNotJson($column): Builder
    {
        // TODO: Implement whereIsNotJson() method.
        $this->builder->setWhereIsNotJson($column);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereIsNotJson($column): Builder
    {
        // TODO: Implement orWhereIsNotJson() method.
        $this->builder->setOrWhereIsNotJson($column);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereJsonValue($column, $value, $path): Builder
    {
        // TODO: Implement whereJsonValue() method.
        $this->builder->setWhereJsonValue($column, $value, $path);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereJsonValue($column, $value, $path): Builder
    {
        // TODO: Implement orWhereJsonValue() method.
        $this->builder->setOrWhereJsonValue($column, $value, $path);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereJsonContains($column, $value, $path = null): Builder
    {
        // TODO: Implement whereJsonContains() method.
        $this->builder->setWhereJsonContains($column, $value, $path);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereJsonContains($column, $value, $path = null): Builder
    {
        // TODO: Implement orWhereJsonContains() method.
        $this->builder->setOrWhereJsonContains($column, $value, $path);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereJsonNotContains($column, $value, $path = null): Builder
    {
        // TODO: Implement whereJsonNotContains() method.
        $this->builder->setWhereJsonNotContains($column, $value, $path);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereJsonNotContains($column, $value, $path = null): Builder
    {
        // TODO: Implement orWhereJsonNotContains() method.
        $this->builder->setWhereJsonNotContains($column, $value, $path);
        return $this;
    }

    public function whereRaw($query, array $bindings = null): Builder
    {
        // TODO: Implement whereRaw() method.
        $this->builder->setWhereRaw($query, $bindings ?: []);
        return $this;
    }

    public function orWhereRaw($query, array $bindings = null): Builder
    {
        // TODO: Implement orWhereRaw() method.
        $this->builder->setOrWhereRaw($query, $bindings ?: []);
        return $this;
    }


    /**
     * @inheritDoc
     */
    public function exists(Closure $callbackQuery): Builder
    {
        // TODO: Implement exists() method.
        $this->builder->setExists($callbackQuery);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orExists(Closure $callbackQuery): Builder
    {
        // TODO: Implement orExists() method.
        $this->builder->setOrExists($callbackQuery);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function notExists(Closure $callbackQuery): Builder
    {
        // TODO: Implement notExists() method.
        $this->builder->setNotExists($callbackQuery);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orNotExists(Closure $callbackQuery): Builder
    {
        // TODO: Implement orNotExists() method.
        $this->builder->setOrNotExists($callbackQuery);
        return $this;
    }


    /**
     * @inheritDoc
     */
    public function having($column, $operator, $value): Builder
    {
        // TODO: Implement having() method.
        $this->builder->setHaving($column, $operator, $value);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function union(Closure $callbackQuery): Builder
    {
        // TODO: Implement union() method.
        $this->builder->setUnion($callbackQuery);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function unionAll(Closure $callbackQuery): Builder
    {
        // TODO: Implement unionAll() method.
        $this->builder->setUnionAll($callbackQuery);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function join($table, $first, $second, $type = 'inner'): Builder
    {
        // TODO: Implement join() method.
        $this->builder->setJoin($table, $first, $second, $type);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function limit($limit): Builder
    {
        // TODO: Implement limit() method.
        $this->builder->setLimit($limit);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orderBy($direction, ...$column): Builder
    {
        // TODO: Implement orderBy() method.
        $this->builder->setOrderBy($direction, ...$column);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function groupBy(...$groups): Builder
    {
        // TODO: Implement groupBy() method.
        $this->builder->setGroupBy(...$groups);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function paginate(int $perPage = DEFAULT_PAGINATION_PER_PAGE, string $tag = "default",
                             string $pageName = 'page', int $page = null): QueryResponse
    {
        // TODO: Implement paginate() method.

        if ($this->builder->getQueryType() !== DriverQueryBuilder::SELECT)
            throw new QueRuntimeException("You can only paginate a [::select] query", "Database DB Error",
                E_USER_ERROR, HTTP::INTERNAL_SERVER_ERROR, PreviousException::getInstance(1));

        $this->pagination['status'] = true;
        $this->pagination['perPage'] = $perPage;
        $this->pagination['tag'] = $tag;
        $this->pagination['pageName'] = $pageName;
        $this->pagination['page'] = $page ?: http()->_get()->get($pageName, 1);
        return $this->exec();
    }

    public function setQueryType(int $queryType)
    {
        // TODO: Implement setQueryType() method.
        $this->builder->setQueryType($queryType);
    }


    public function setQuery(string $query): void
    {
        // TODO: Implement setQuery() method.
        $this->builder->setQuery($query);
    }

    public function getQuery(): string
    {
        // TODO: Implement getQuery() method.
        return $this->builder->getQuery();
    }

    public function setQueryBindings(array $bindings): void
    {
        // TODO: Implement setQueryBindings() method.
        $this->builder->setQueryBindings($bindings);
    }

    public function getQueryBindings(): array
    {
        // TODO: Implement getQueryBindings() method.
        return $this->builder->getQueryBindings();
    }


    /**
     * @inheritDoc
     */
    public function exec(): QueryResponse
    {
        // TODO: Implement exec() method.
        switch ($this->builder->getQueryType()) {
            case DriverQueryBuilder::INSERT:
                return $this->insert();
            case DriverQueryBuilder::SELECT:
                return $this->selectQuery();
            case DriverQueryBuilder::UPDATE:
                return $this->update();
            case DriverQueryBuilder::DELETE:
                return $this->delete();
            case DriverQueryBuilder::COUNT:
                return $this->count();
            case DriverQueryBuilder::CHECK:
                return $this->check();
            case DriverQueryBuilder::AVG:
                return $this->avg();
            case DriverQueryBuilder::SUM:
                return $this->sum();
            case DriverQueryBuilder::RAW_SELECT:
                return $this->raw_select();
            case DriverQueryBuilder::RAW_OBJECT:
                return $this->raw_object();
            case DriverQueryBuilder::RAW_QUERY:
                return $this->raw_query();
            case DriverQueryBuilder::SHOW:
                return $this->show_table();
            default:
                throw new QueRuntimeException("Invalid query type", "Query Builder Error",
                    E_USER_ERROR, HTTP::INTERNAL_SERVER_ERROR, PreviousException::getInstance(1));

        }
    }

    /**
     * @return QueryResponse
     */
    private function insert(): QueryResponse
    {
        if (!isset(self::$primaryKeys[$this->builder->getTable()])) {

            $this->builder->setQueryType(DriverQueryBuilder::SHOW);
            $showTable = $this->show_table();
            $this->builder->setQueryType(DriverQueryBuilder::INSERT);

            self::$primaryKeys[$this->builder->getTable()] = $showTable->isSuccessful() ? $showTable->getQueryResponse() : 'id';
        }

        $model = \model(config("database.default.model"));

        if ($model !== null) {
            if (($implements = class_implements($model)) && in_array(Model::class, $implements)) {
                $columns = (object) $this->builder->getColumns();
                $model = new $model($columns, $this->builder->getTable(), self::$primaryKeys[$this->builder->getTable()]);
            }
        }

        if (!$model instanceof Model) {
            $columns = (object) $this->builder->getColumns();
            $model = new \que\database\model\CentralModel($columns, $this->builder->getTable(), self::$primaryKeys[$this->builder->getTable()]);
        }

        $this->builder->setColumns($this->normalize_data($this->builder->getColumns()));

        return $this->insertOps($model);
    }

    /**
     * @param Model $model
     * @param bool $retrying
     * @param Observer|null $observer
     * @param int $attempts
     * @return QueryResponse
     */
    private function insertOps(Model $model, bool $retrying = false,
                               Observer $observer = null, int $attempts = 0): QueryResponse
    {
        $this->builder->buildQuery();

        if ($observer === null) {
            $observer = config("database.observers.{$this->builder->getTable()}");
            if ($observer !== null && class_exists($observer, true) &&
                (($implements = class_implements($observer)) &&
                    in_array(interfaces\observer\Observer::class, $implements))) {

                $observer = new $observer(new ObserverSignal());
                if ($observer instanceof Observer) {
                    //Notify observer that insert operation has started
                    $observer->onCreating($model);
                }
            }

        }

        if ($observer instanceof Observer) {

            //Check if observer wants to discontinue the insert operation
            if (!$observer->getSignal()->isContinueOperation()) {

                return new QueryResponse($this->getCustomDriverResponse($this->builder, [
                    "Observer for '{$this->builder->getTable()}' table stopped insert operation" . (
                        $observer->getSignal()->getReason() ? " due to: {$observer->getSignal()->getReason()}" : ""
                    )
                ], "00101"), $this->builder->getQueryType(), $this->builder->getTable(), $model->getPrimaryKey());
            }

            //Begin a transaction so that we can roll back if the developer
            //asks us to do so via the observer signal
            if (!$this->query->isTransEnabled()) $this->query->setTransEnabled(true);
            $this->query->transBegin();
        }

        $response = $this->driver->exec($this->builder);

        if (!$response->isSuccessful()) {

            if ($observer instanceof Observer && $model instanceof Model) {

                //Check that operation in not already on retry mode
                if (!$retrying) {

                    //Notify observer that operation failed
                    $observer->onCreateFailed($model, $response->getErrors(), $response->getErrorCode());

                    $signal = $observer->getSignal();

                    //Check if observer wants to retry the operation again
                    if ($signal->isRetryOperation()) {

                        //Notify observer that retry operation has started
                        $observer->onCreateRetryStarted($model);

                        try {

                            $totalAttempts = 0;

                            $retryResponse = retry(function ($attempt) use ($observer, $model, &$totalAttempts) {

                                $totalAttempts = $attempt;

                                return $this->insertOps($model,true, $observer, $attempt);

                            }, $signal->getTrials(), $signal->getInterval() * 1000, function (QueryResponse $retryResponse) {
                                return $retryResponse->isSuccessful();
                            });

                            //Notify observer that retry operation has completed
                            $observer->onCreateRetryComplete($model, $retryResponse instanceof QueryResponse ?
                                $retryResponse->isSuccessful() : false, $totalAttempts);

                            if ($retryResponse instanceof QueryResponse) return $retryResponse;

                        } catch (Exception $e) {
                        }
                    }
                }
            }

            if ($retrying && $attempts < $observer->getSignal()->getTrials()) {

                $model->offsetSet($model->getPrimaryKey(), $response->getLastInsertID());
                if ($attempts <= 1) $model->refresh();
                $response->setResponse([$model->getObject()]);
                return new QueryResponse($response, $this->builder->getQueryType(), $this->builder->getTable(), $model->getPrimaryKey());
            }

            $this->query->transComplete();

            if ($this->driver->isInDebugMode()) {

                $errors = serializer_recursive($response->getErrors(), " -- ", function ($error) {
                    return !empty($error);
                });

                throw new QueRuntimeException("Error: {$errors} \nDB: '{$response->getQueryString()}'\n",
                    "Database Error", E_USER_ERROR, 0,
                    PreviousException::getInstance());
            }

        } elseif ($observer instanceof Observer && $model instanceof Model) {
            $model->offsetSet($model->getPrimaryKey(), $response->getLastInsertID());
            $model->refresh();
            $observer->onCreated($model);
        }

        //Check if observer wants to undo the insert operation
        if ($observer instanceof Observer && $observer->getSignal()->isUndoOperation()) {

            $this->query->transRollBack();

            return new QueryResponse($this->getCustomDriverResponse($this->builder, [
                "Observer for '{$this->builder->getTable()}' table asked to undo the insert operation" . (
                    $observer->getSignal()->getReason() ? " due to: {$observer->getSignal()->getReason()}" : ""
                )
            ], "00101"), $this->builder->getQueryType(), $this->builder->getTable(), $model->getPrimaryKey());

        } else {

            //Here we complete the transaction, since the developer didn't ask us to undo the operation
            $this->query->transComplete();
        }

        $response->setResponse([$model->getObject()]);

        return new QueryResponse($response, $this->builder->getQueryType(), $this->builder->getTable(), $model->getPrimaryKey());
    }

    /**
     * @return QueryResponse
     */
    private function delete(): QueryResponse
    {

        if (!isset(self::$primaryKeys[$this->builder->getTable()])) {

            $this->builder->setQueryType(DriverQueryBuilder::SHOW);
            $showTable = $this->show_table();
            $this->builder->setQueryType(DriverQueryBuilder::DELETE);
            self::$primaryKeys[$this->builder->getTable()] = $showTable->isSuccessful() ? $showTable->getQueryResponse() : 'id';
        }

        $this->builder->setQueryType(DriverQueryBuilder::SELECT);
        $record = $this->selectQuery();
        $this->builder->setQueryType(DriverQueryBuilder::DELETE);

        if (!$record->isSuccessful()) {

            $this->builder->buildQuery();
            return new QueryResponse($this->getCustomDriverResponse($this->builder, [$record->getQueryError()],
                $record->getQueryErrorCode()), $this->builder->getQueryType(), $this->builder->getTable(), self::$primaryKeys[$this->builder->getTable()]);
        }

        $modelCollection = $record->getAllWithModel(self::$primaryKeys[$this->builder->getTable()]);

        return $this->deleteOps($modelCollection);
    }

    /**
     * @param ModelCollection|Model[] $modelCollection
     * @param Observer|null $observer
     * @param bool $retrying
     * @param int $attempts
     * @return QueryResponse
     */
    private function deleteOps(ModelCollection $modelCollection, Observer $observer = null,
                               bool $retrying = false, int $attempts = 0): QueryResponse
    {

        if ($observer === null) {

            $observer = config("database.observers.{$this->builder->getTable()}");

            if ($observer !== null && class_exists($observer, true)) {

                if (($implements = class_implements($observer)) &&
                    in_array(interfaces\observer\Observer::class, $implements)) {

                    $observer = new $observer(new ObserverSignal());
                    if ($observer instanceof Observer) {
                        //Notify observer that insert operation has started
                        $observer->onDeleting($modelCollection);
                    }
                }
            }
        }

        $this->builder->clearWhereQuery();

        foreach ($modelCollection as $model) {
            if (!$model instanceof Model) continue;
            $this->builder->setOrWhere($model->getPrimaryKey(), $model->getValue($model->getPrimaryKey()));
        }

        $this->builder->buildQuery();

        if ($observer instanceof Observer) {
            //Check if observer wants to discontinue the delete operation
            if (!$observer->getSignal()->isContinueOperation()) {

                return new QueryResponse($this->getCustomDriverResponse($this->builder, [
                    "Observer for '{$this->builder->getTable()}' table stopped delete operation" . (
                        $observer->getSignal()->getReason() ? " due to: {$observer->getSignal()->getReason()}" : ""
                    )
                ], "00101"), $this->builder->getQueryType(), $this->builder->getTable(), self::$primaryKeys[$this->builder->getTable()]);
            }

            //Begin a transaction so that we can roll back if the developer
            //asks us to do so via the observer signal
            if (!$this->query->isTransEnabled()) $this->query->setTransEnabled(true);
            $this->query->transBegin();
        }

        if ($modelCollection->isEmpty()) {

            return new QueryResponse($this->getCustomDriverResponse($this->builder, [
                "Observer for '{$this->builder->getTable()}' table removed all records to be deleted thereby stopping the delete operation"
            ], "00101"), $this->builder->getQueryType(), $this->builder->getTable(), self::$primaryKeys[$this->builder->getTable()]);
        }

        $response = $this->driver->exec($this->builder);

        if (!$response->isSuccessful()) {

            //Check that operation in not already on retry mode
            if (!$retrying && $observer instanceof Observer) {

                //Notify observer that operation failed
                $observer->onDeleteFailed($modelCollection, $response->getErrors(), $response->getErrorCode());

                $signal = $observer->getSignal();

                //Check if observer wants to retry the operation again
                if ($signal->isRetryOperation()) {

                    //Notify observer that retry operation has started
                    $observer->onDeleteRetryStarted($modelCollection);

                    try {

                        $totalAttempts = 0;

                        $retryResponse = retry(function ($attempt) use ($modelCollection, $observer, &$totalAttempts) {

                            $totalAttempts = $attempt;

                            return $this->deleteOps($modelCollection, $observer, true, $attempt);

                        }, $signal->getTrials(), $signal->getInterval() * 1000, function (QueryResponse $retryResponse) {
                            return $retryResponse->isSuccessful();
                        });

                        //Notify observer that retry operation has completed
                        $observer->onDeleteRetryComplete($modelCollection, $retryResponse instanceof QueryResponse ?
                            $retryResponse->isSuccessful() : false, $totalAttempts);

                        if ($retryResponse instanceof QueryResponse) return $retryResponse;

                    } catch (Exception $e) {
                    }
                }
            }

            if ($retrying && $attempts < $observer->getSignal()->getTrials())
                return new QueryResponse($response, $this->builder->getQueryType(), $this->builder->getTable(), self::$primaryKeys[$this->builder->getTable()]);

            $this->query->transRollBack();

            if ($this->driver->isInDebugMode()) {

                $errors = serializer_recursive($response->getErrors(), " -- ", function ($error) {
                    return !empty($error);
                });

                throw new QueRuntimeException("Error: {$errors} \nDB: '{$response->getQueryString()}'\n",
                    "Database Error", E_USER_ERROR, 0,
                    PreviousException::getInstance());
            }

        } elseif ($observer instanceof Observer && $modelCollection instanceof ModelCollection) $observer->onDeleted($modelCollection);

        if ($observer instanceof Observer) {

            //Check if observer wants to undo the delete operation
            if ($observer->getSignal()->isUndoOperation()) {

                $this->query->transRollBack();

                return new QueryResponse($this->getCustomDriverResponse($this->builder, [
                    "Observer for '{$this->builder->getTable()}' table asked to undo the delete operation" . (
                        $observer->getSignal()->getReason() ? " due to: {$observer->getSignal()->getReason()}" : ""
                    )
                ], "00101"), $this->builder->getQueryType(), $this->builder->getTable(), self::$primaryKeys[$this->builder->getTable()]);
            } else {

                //Here we complete the transaction, since the developer
                //didn't as us to undo the operation
                $this->query->transComplete();
            }
        }

        return new QueryResponse($response, $this->builder->getQueryType(), $this->builder->getTable(), self::$primaryKeys[$this->builder->getTable()]);
    }

    /**
     * @return QueryResponse
     */
    private function selectQuery(): QueryResponse
    {

        if ($this->pagination['status'] === true) {

            $this->builder->setQueryType(DriverQueryBuilder::COUNT);
            $count = $this->count();
            $this->builder->setQueryType(DriverQueryBuilder::SELECT);

            $paginator = new Paginator();
            $paginator->records(($count->isSuccessful() ? $count->getQueryResponse() : 0));
            $paginator->recordsPerPage($this->pagination['perPage']);
            $paginator->variable_name($this->pagination['pageName']);
            $paginator->set_page($this->pagination['page']);

            if ($this->pagination['page'] > ($totalPages = $paginator->getTotalPages())) {
                $this->pagination['page'] = $totalPages;
            }

            $limit = [(int) (($this->pagination['page'] - 1) * $this->pagination['perPage']), (int) $this->pagination['perPage']];
            $this->builder->setLimit($limit);

            Pagination::getInstance()->add($paginator, $this->pagination['tag']);

            $this->pagination['status'] = false;
        }

        if (!isset(self::$primaryKeys[$this->builder->getTable()])) {

            $this->builder->setQueryType(DriverQueryBuilder::SHOW);
            $showTable = $this->show_table();
            $this->builder->setQueryType(DriverQueryBuilder::SELECT);
            self::$primaryKeys[$this->builder->getTable()] = $showTable->isSuccessful() ? $showTable->getQueryResponse() : 'id';
        }

        if (empty($this->builder->getSelect())) $this->builder->setSelect('*');

        $this->builder->buildQuery();

        $response = $this->driver->exec($this->builder);

        if (!$response->isSuccessful()) {

            if ($this->driver->isInDebugMode()) {

                $errors = serializer_recursive($response->getErrors(), " -- ", function ($error) {
                    return !empty($error);
                });

                throw new QueRuntimeException("Error: {$errors} \nDB: '{$response->getQueryString()}'\n",
                    "Database Error", E_USER_ERROR, 0,
                    PreviousException::getInstance());
            }
        }

        return new QueryResponse($response, $this->builder->getQueryType(), $this->builder->getTable(), self::$primaryKeys[$this->builder->getTable()]);
    }

    /**
     * @return QueryResponse
     */
    private function raw_select(): QueryResponse
    {

        $response = $this->driver->exec($this->builder);

        if (!$response->isSuccessful()) {

            if ($this->driver->isInDebugMode()) {

                $errors = serializer_recursive($response->getErrors(), " -- ", function ($error) {
                    return !empty($error);
                });

                throw new QueRuntimeException("Error: {$errors} \nDB: '{$response->getQueryString()}'\n",
                    "Database Error", E_USER_ERROR, 0,
                    PreviousException::getInstance());
            }
        }

        return new QueryResponse($response, $this->builder->getQueryType(), $this->builder->getTable(), 'id');
    }

    /**
     * @return QueryResponse
     */
    private function raw_query(): QueryResponse
    {

        $response = $this->driver->exec($this->builder);

        if (!$response->isSuccessful()) {

            if ($this->driver->isInDebugMode()) {

                $errors = serializer_recursive($response->getErrors(), " -- ", function ($error) {
                    return !empty($error);
                });

                throw new QueRuntimeException("Error: {$errors} \nDB: '{$response->getQueryString()}'\n",
                    "Database Error", E_USER_ERROR, 0,
                    PreviousException::getInstance());
            }
        }

        return new QueryResponse($response, $this->builder->getQueryType(), $this->builder->getTable(), 'id');
    }

    /**
     * @return QueryResponse
     */
    private function raw_object(): QueryResponse
    {

        $response = $this->driver->exec($this->builder);

        if (!$response->isSuccessful()) {

            if ($this->driver->isInDebugMode()) {

                $errors = serializer_recursive($response->getErrors(), " -- ", function ($error) {
                    return !empty($error);
                });

                throw new QueRuntimeException("Error: {$errors} \nDB: '{$response->getQueryString()}'\n",
                    "Database Error", E_USER_ERROR, 0,
                    PreviousException::getInstance());
            }
        }

        return new QueryResponse($response, $this->builder->getQueryType(), $this->builder->getTable(), 'id');
    }

    /**
     * @return QueryResponse
     */
    private function update(): QueryResponse
    {

        if (!isset(self::$primaryKeys[$this->builder->getTable()])) {

            $this->builder->setQueryType(DriverQueryBuilder::SHOW);
            $showTable = $this->show_table();
            $this->builder->setQueryType(DriverQueryBuilder::UPDATE);
            self::$primaryKeys[$this->builder->getTable()] = $showTable->isSuccessful() ? $showTable->getQueryResponse() : 'id';
        }

        $this->builder->setSelect('*')->setQueryType(DriverQueryBuilder::SELECT);
        $record = $this->selectQuery();
        $this->builder->setQueryType(DriverQueryBuilder::UPDATE);

        if (!$record->isSuccessful()) {

            $this->builder->buildQuery();
            return new QueryResponse($this->getCustomDriverResponse($this->builder, [$record->getQueryError()],
                $record->getQueryErrorCode()), $this->builder->getQueryType(), $this->builder->getTable(), self::$primaryKeys[$this->builder->getTable()]);
        }

        $modelCollection = $record->getAllWithModel(self::$primaryKeys[$this->builder->getTable()]);

        $this->builder->setColumns($this->normalize_data($this->builder->getColumns()));

        return $this->updateOps($modelCollection);
    }

    /**
     * @param ModelCollection $oldModelCollection
     * @param Observer|null $observer
     * @param bool $retrying
     * @param int $attempts
     * @return QueryResponse
     */
    private function updateOps(ModelCollection $oldModelCollection, Observer $observer = null,
                               bool $retrying = false, int $attempts = 0): QueryResponse
    {

        $newModelCollection = clone $oldModelCollection;
        foreach ($oldModelCollection as $m) {
            if (!$m instanceof Model) continue;
            $newModelCollection->detach($m);
            $newModelCollection->addModel(clone $m);
            $newModelCollection->map(function (Model $model) {
                foreach ($this->builder->getColumns() as $column => $value)
                    $model->offsetSet($column, $value);
            });
        }


        if ($observer === null) {

            $observer = config("database.observers.{$this->builder->getTable()}");

            if ($observer !== null && class_exists($observer, true)) {

                if (($implements = class_implements($observer)) &&
                    in_array(interfaces\observer\Observer::class, $implements)) {

                    $observer = new $observer(new ObserverSignal());
                    if ($observer instanceof Observer) {
                        //Notify observer that insert operation has started
                        $observer->onUpdating($newModelCollection, $oldModelCollection);
                    }
                }
            }
        }

        $this->builder->clearWhereQuery();

        foreach ($newModelCollection as $model) {
            if (!$model instanceof Model) continue;
            $this->builder->setOrWhere($model->getPrimaryKey(), $model->getValue($model->getPrimaryKey()));
        }

        $this->builder->buildQuery();

        if ($observer instanceof Observer) {
            //Check if observer wants to discontinue the update operation
            if (!$observer->getSignal()->isContinueOperation()) {

                return new QueryResponse($this->getCustomDriverResponse($this->builder, [
                    "Observer for '{$this->builder->getTable()}' table stopped update operation" . (
                        $observer->getSignal()->getReason() ? " due to: {$observer->getSignal()->getReason()}" : ""
                    )
                ], "00101"), $this->builder->getQueryType(), $this->builder->getTable(), self::$primaryKeys[$this->builder->getTable()]);
            }

            //Begin a transaction so that we can roll back if the developer
            //asks us to do so via the observer signal
            if (!$this->query->isTransEnabled()) $this->query->setTransEnabled(true);
            $this->query->transBegin();
        }

        if ($oldModelCollection->isEmpty()) {

            return new QueryResponse($this->getCustomDriverResponse($this->builder, [
                "Observer for '{$this->builder->getTable()}' table removed all records to be updated thereby stopping the update operation"
            ], "00101"), $this->builder->getQueryType(), $this->builder->getTable(), self::$primaryKeys[$this->builder->getTable()]);
        }

        $response = $this->driver->exec($this->builder);

        if (!$response->isSuccessful()) {

            //Check that operation in not already on retry mode
            if (!$retrying && $observer instanceof Observer) {

                //Notify observer that operation failed
                $observer->onUpdateFailed($oldModelCollection, $response->getErrors(), $response->getErrorCode());

                $signal = $observer->getSignal();

                //Check if observer wants to retry the operation again
                if ($signal->isRetryOperation()) {

                    //Notify observer that retry operation has started
                    $observer->onUpdateRetryStarted($oldModelCollection);

                    try {

                        $totalAttempts = 0;

                        $retryResponse = retry(function ($attempt) use ($oldModelCollection, $observer, &$totalAttempts) {

                            $totalAttempts = $attempt;

                            return $this->updateOps($oldModelCollection, $observer, true, $attempt);

                        }, $signal->getTrials(), $signal->getInterval() * 1000, function (QueryResponse $retryResponse) {
                            return $retryResponse->isSuccessful();
                        });

                        //Notify observer that retry operation has completed
                        $observer->onUpdateRetryComplete($oldModelCollection, $retryResponse instanceof QueryResponse ?
                            $retryResponse->isSuccessful() : false, $totalAttempts);

                        if ($retryResponse instanceof QueryResponse) return $retryResponse;

                    } catch (Exception $e) {
                    }
                }
            }

            if ($retrying && $attempts < $observer->getSignal()->getTrials()) {
//                if ($attempts <= 1) $newModelCollection->refresh();
                $response->setResponse([array_map(function ($data) {
                    return (object) $data;
                }, $newModelCollection->getArray())]);
                return new QueryResponse($response, $this->builder->getQueryType(), $this->builder->getTable(), self::$primaryKeys[$this->builder->getTable()]);
            }

            $this->query->transRollBack();

            if ($this->driver->isInDebugMode()) {

                $errors = serializer_recursive($response->getErrors(), " -- ", function ($error) {
                    return !empty($error);
                });

                throw new QueRuntimeException("Error: {$errors} \nDB: '{$response->getQueryString()}'\n",
                    "Database Error", E_USER_ERROR, 0,
                    PreviousException::getInstance());
            }

        } elseif ($observer instanceof Observer && $oldModelCollection instanceof ModelCollection) {
//            $newModelCollection->refresh();
            $observer->onUpdated($newModelCollection, $oldModelCollection);
        }

        if ($observer instanceof Observer) {

            //Check if observer wants to undo the update operation
            if ($observer->getSignal()->isUndoOperation()) {

                $this->query->transRollBack();

                return new QueryResponse($this->getCustomDriverResponse($this->builder, [
                    "Observer for '{$this->builder->getTable()}' table asked to undo the update operation" . (
                        $observer->getSignal()->getReason() ? " due to: {$observer->getSignal()->getReason()}" : ""
                    )
                ], "00101"), $this->builder->getQueryType(), $this->builder->getTable(), self::$primaryKeys[$this->builder->getTable()]);
            } else {

                //Here we complete the transaction, since the developer
                //didn't as us to undo the operation
                $this->query->transComplete();
            }
        }

        $response->setResponse([array_map(function ($data) {
            return (object) $data;
        }, $newModelCollection->getArray())]);

        return new QueryResponse($response, $this->builder->getQueryType(), $this->builder->getTable(), self::$primaryKeys[$this->builder->getTable()]);
    }

    /**
     * @return QueryResponse
     */
    private function check(): QueryResponse
    {

        if (!isset(self::$primaryKeys[$this->builder->getTable()])) {

            $this->builder->setQueryType(DriverQueryBuilder::SHOW);
            $showTable = $this->show_table();
            $this->builder->setQueryType(DriverQueryBuilder::CHECK);
            self::$primaryKeys[$this->builder->getTable()] = $showTable->isSuccessful() ? ($showTable->getQueryResponse() ?: 'id') : 'id';
        }

        if (empty($this->builder->getSelect())) $this->builder->setSelect(self::$primaryKeys[$this->builder->getTable()]);

        $this->builder->buildQuery();

        $response = $this->driver->exec($this->builder);

        return new QueryResponse($response, $this->builder->getQueryType(), $this->builder->getTable(), self::$primaryKeys[$this->builder->getTable()]);
    }

    /**
     * @return QueryResponse
     */
    private function count(): QueryResponse
    {

        if (!isset(self::$primaryKeys[$this->builder->getTable()])) {

            $this->builder->setQueryType(DriverQueryBuilder::SHOW);
            $showTable = $this->show_table();
            $this->builder->setQueryType(DriverQueryBuilder::COUNT);
            self::$primaryKeys[$this->builder->getTable()] = $showTable->isSuccessful() ? ($showTable->getQueryResponse() ?: 'id') : 'id';
        }

        if (empty($this->builder->getSelect())) $this->builder->setSelect(self::$primaryKeys[$this->builder->getTable()]);

        $this->builder->buildQuery();

        $response = $this->driver->exec($this->builder);

        return new QueryResponse($response, $this->builder->getQueryType(), $this->builder->getTable(), self::$primaryKeys[$this->builder->getTable()]);
    }

    /**
     * @return QueryResponse
     */
    private function avg(): QueryResponse
    {

        if (!isset(self::$primaryKeys[$this->builder->getTable()])) {

            $this->builder->setQueryType(DriverQueryBuilder::SHOW);
            $showTable = $this->show_table();
            $this->builder->setQueryType(DriverQueryBuilder::AVG);
            self::$primaryKeys[$this->builder->getTable()] = $showTable->isSuccessful() ? ($showTable->getQueryResponse() ?: 'id') : 'id';
        }

        if (empty($this->builder->getSelect())) $this->builder->setSelect(self::$primaryKeys[$this->builder->getTable()]);

        $this->builder->buildQuery();

        $response = $this->driver->exec($this->builder);

        return new QueryResponse($response, $this->builder->getQueryType(), $this->builder->getTable(), self::$primaryKeys[$this->builder->getTable()]);
    }

    /**
     * @return QueryResponse
     */
    private function sum(): QueryResponse
    {

        if (!isset(self::$primaryKeys[$this->builder->getTable()])) {

            $this->builder->setQueryType(DriverQueryBuilder::SHOW);
            $showTable = $this->show_table();
            $this->builder->setQueryType(DriverQueryBuilder::SUM);
            self::$primaryKeys[$this->builder->getTable()] = $showTable->isSuccessful() ? ($showTable->getQueryResponse() ?: 'id') : 'id';
        }

        if (empty($this->builder->getSelect())) $this->builder->setSelect(self::$primaryKeys[$this->builder->getTable()]);

        $this->builder->buildQuery();

        $response = $this->driver->exec($this->builder);

        return new QueryResponse($response, $this->builder->getQueryType(), $this->builder->getTable(), self::$primaryKeys[$this->builder->getTable()]);
    }

    /**
     * @return QueryResponse
     */
    private function show_table(): QueryResponse
    {
        $this->builder->buildQuery();
        $response = $this->driver->exec($this->builder);
        return new QueryResponse($response, $this->builder->getQueryType(), $this->builder->getTable(), 'id');
    }

    /**
     * @param array $data
     * @param bool $recursive
     * @return array
     */
    private function normalize_data(array $data, bool $recursive = false): array
    {
        if ($recursive) {
            array_callback_recursive($data, function ($value) {
                if (is_array($value)) return json_encode($value);
                if ($value instanceof JsonSerializable) return json_encode($value);
                return is_object($value) ? $this->mark_down($value) :
                    (is_string($value) ? $this->query->escape_string($value) : $value);
            });
        } else {
            array_callback($data, function ($value) {
                if (is_array($value)) return json_encode($value);
                if ($value instanceof JsonSerializable) return json_encode($value);
                return is_object($value) ? $this->mark_down($value) :
                    (is_string($value) ? $this->query->escape_string($value) : $value);
            });
        }
        return $data;
    }

    /**
     * @param $data
     * @return string
     */
    private function mark_down($data)
    {
        $type = gettype($data);
        $can_wakeup = "false";
        if (is_object($data) && (($class_name = get_class($data)) != stdClass::class)
            && class_exists($class_name, true)) {
            $type = "class";
            if (method_exists($data, '__wakeup') &&
                is_callable([$data, '__wakeup'])) $can_wakeup = "true";
        }
        $data = serialize($data);
        return "[{$data}]({$type})({$can_wakeup})";
    }

    /**
     * @param DriverQueryBuilder $builder
     * @param array $errors
     * @param string $errorCode
     * @return DriverResponse
     */
    private function getCustomDriverResponse(DriverQueryBuilder $builder, array $errors, string $errorCode)
    {

        $response = new class implements DriverResponse {

            public array $errors = [];
            public string $errorCode = "";
            public string $query = '';

            /**
             * @inheritDoc
             */
            public function isSuccessful(): bool
            {
                // TODO: Implement isSuccessful() method.
                return false;
            }

            /**
             * @inheritDoc
             */
            public function getResponse()
            {
                // TODO: Implement getResponse() method.
                return null;
            }

            /**
             * @inheritDoc
             */
            public function getLastInsertID(): int
            {
                // TODO: Implement getLastInsertID() method.
                return 0;
            }

            /**
             * @inheritDoc
             */
            public function getAffectedRows(): int
            {
                // TODO: Implement getAffectedRows() method.
                return 0;
            }

            /**
             * @inheritDoc
             */
            public function getErrors(): array
            {
                // TODO: Implement getErrors() method.
                return $this->errors;
            }

            /**
             * @inheritDoc
             */
            public function getErrorCode(): string
            {
                // TODO: Implement getErrorCode() method.
                return $this->errorCode;
            }

            /**
             * @inheritDoc
             */
            public function getQueryString(): string
            {
                // TODO: Implement getQueryString() method.
                return $this->query;
            }

            public function setResponse($response): void
            {
                // TODO: Implement setResponse() method.
            }
        };

        $response->query = $this->interpolateQuery($builder->getQuery(), $builder->getQueryBindings());
        $response->errors = $errors;
        $response->errorCode = $errorCode;

        return $response;
    }


    /**
     * @param string $query
     * @param array $params
     * @return string|string[]
     */
    private function interpolateQuery(string $query, array $params) {
        foreach ($params as $key => $value) {
            if ($value === null) $value = 'NULL';
            elseif (is_bool($value)) $value = $value ? 1 : 0;
            elseif (!is_numeric($value)) $value = "'{$value}'";
            $query = str_replace_first($key, "{$value}", $query);
        }
        return $query;
    }
}
