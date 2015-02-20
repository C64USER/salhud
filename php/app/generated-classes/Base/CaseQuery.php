<?php

namespace Base;

use \Case as ChildCase;
use \CaseQuery as ChildCaseQuery;
use \Exception;
use \PDO;
use Map\CaseTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'case' table.
 *
 *
 *
 * @method     ChildCaseQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildCaseQuery orderByYear($order = Criteria::ASC) Order by the year column
 * @method     ChildCaseQuery orderByDiseaseId($order = Criteria::ASC) Order by the disease_id column
 * @method     ChildCaseQuery orderByTownId($order = Criteria::ASC) Order by the town_id column
 *
 * @method     ChildCaseQuery groupById() Group by the id column
 * @method     ChildCaseQuery groupByYear() Group by the year column
 * @method     ChildCaseQuery groupByDiseaseId() Group by the disease_id column
 * @method     ChildCaseQuery groupByTownId() Group by the town_id column
 *
 * @method     ChildCaseQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCaseQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCaseQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCaseQuery leftJoinDisease($relationAlias = null) Adds a LEFT JOIN clause to the query using the Disease relation
 * @method     ChildCaseQuery rightJoinDisease($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Disease relation
 * @method     ChildCaseQuery innerJoinDisease($relationAlias = null) Adds a INNER JOIN clause to the query using the Disease relation
 *
 * @method     ChildCaseQuery leftJoinTown($relationAlias = null) Adds a LEFT JOIN clause to the query using the Town relation
 * @method     ChildCaseQuery rightJoinTown($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Town relation
 * @method     ChildCaseQuery innerJoinTown($relationAlias = null) Adds a INNER JOIN clause to the query using the Town relation
 *
 * @method     \DiseaseQuery|\TownQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildCase findOne(ConnectionInterface $con = null) Return the first ChildCase matching the query
 * @method     ChildCase findOneOrCreate(ConnectionInterface $con = null) Return the first ChildCase matching the query, or a new ChildCase object populated from the query conditions when no match is found
 *
 * @method     ChildCase findOneById(int $id) Return the first ChildCase filtered by the id column
 * @method     ChildCase findOneByYear(int $year) Return the first ChildCase filtered by the year column
 * @method     ChildCase findOneByDiseaseId(int $disease_id) Return the first ChildCase filtered by the disease_id column
 * @method     ChildCase findOneByTownId(int $town_id) Return the first ChildCase filtered by the town_id column *

 * @method     ChildCase requirePk($key, ConnectionInterface $con = null) Return the ChildCase by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCase requireOne(ConnectionInterface $con = null) Return the first ChildCase matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCase requireOneById(int $id) Return the first ChildCase filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCase requireOneByYear(int $year) Return the first ChildCase filtered by the year column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCase requireOneByDiseaseId(int $disease_id) Return the first ChildCase filtered by the disease_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCase requireOneByTownId(int $town_id) Return the first ChildCase filtered by the town_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCase[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildCase objects based on current ModelCriteria
 * @method     ChildCase[]|ObjectCollection findById(int $id) Return ChildCase objects filtered by the id column
 * @method     ChildCase[]|ObjectCollection findByYear(int $year) Return ChildCase objects filtered by the year column
 * @method     ChildCase[]|ObjectCollection findByDiseaseId(int $disease_id) Return ChildCase objects filtered by the disease_id column
 * @method     ChildCase[]|ObjectCollection findByTownId(int $town_id) Return ChildCase objects filtered by the town_id column
 * @method     ChildCase[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class CaseQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\CaseQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'salhud', $modelName = '\\Case', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCaseQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCaseQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildCaseQuery) {
            return $criteria;
        }
        $query = new ChildCaseQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildCase|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CaseTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CaseTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildCase A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, year, disease_id, town_id FROM case WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildCase $obj */
            $obj = new ChildCase();
            $obj->hydrate($row);
            CaseTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildCase|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildCaseQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CaseTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildCaseQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CaseTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCaseQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CaseTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CaseTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CaseTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the year column
     *
     * Example usage:
     * <code>
     * $query->filterByYear(1234); // WHERE year = 1234
     * $query->filterByYear(array(12, 34)); // WHERE year IN (12, 34)
     * $query->filterByYear(array('min' => 12)); // WHERE year > 12
     * </code>
     *
     * @param     mixed $year The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCaseQuery The current query, for fluid interface
     */
    public function filterByYear($year = null, $comparison = null)
    {
        if (is_array($year)) {
            $useMinMax = false;
            if (isset($year['min'])) {
                $this->addUsingAlias(CaseTableMap::COL_YEAR, $year['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($year['max'])) {
                $this->addUsingAlias(CaseTableMap::COL_YEAR, $year['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CaseTableMap::COL_YEAR, $year, $comparison);
    }

    /**
     * Filter the query on the disease_id column
     *
     * Example usage:
     * <code>
     * $query->filterByDiseaseId(1234); // WHERE disease_id = 1234
     * $query->filterByDiseaseId(array(12, 34)); // WHERE disease_id IN (12, 34)
     * $query->filterByDiseaseId(array('min' => 12)); // WHERE disease_id > 12
     * </code>
     *
     * @see       filterByDisease()
     *
     * @param     mixed $diseaseId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCaseQuery The current query, for fluid interface
     */
    public function filterByDiseaseId($diseaseId = null, $comparison = null)
    {
        if (is_array($diseaseId)) {
            $useMinMax = false;
            if (isset($diseaseId['min'])) {
                $this->addUsingAlias(CaseTableMap::COL_DISEASE_ID, $diseaseId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($diseaseId['max'])) {
                $this->addUsingAlias(CaseTableMap::COL_DISEASE_ID, $diseaseId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CaseTableMap::COL_DISEASE_ID, $diseaseId, $comparison);
    }

    /**
     * Filter the query on the town_id column
     *
     * Example usage:
     * <code>
     * $query->filterByTownId(1234); // WHERE town_id = 1234
     * $query->filterByTownId(array(12, 34)); // WHERE town_id IN (12, 34)
     * $query->filterByTownId(array('min' => 12)); // WHERE town_id > 12
     * </code>
     *
     * @see       filterByTown()
     *
     * @param     mixed $townId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCaseQuery The current query, for fluid interface
     */
    public function filterByTownId($townId = null, $comparison = null)
    {
        if (is_array($townId)) {
            $useMinMax = false;
            if (isset($townId['min'])) {
                $this->addUsingAlias(CaseTableMap::COL_TOWN_ID, $townId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($townId['max'])) {
                $this->addUsingAlias(CaseTableMap::COL_TOWN_ID, $townId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CaseTableMap::COL_TOWN_ID, $townId, $comparison);
    }

    /**
     * Filter the query by a related \Disease object
     *
     * @param \Disease|ObjectCollection $disease The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildCaseQuery The current query, for fluid interface
     */
    public function filterByDisease($disease, $comparison = null)
    {
        if ($disease instanceof \Disease) {
            return $this
                ->addUsingAlias(CaseTableMap::COL_DISEASE_ID, $disease->getId(), $comparison);
        } elseif ($disease instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CaseTableMap::COL_DISEASE_ID, $disease->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByDisease() only accepts arguments of type \Disease or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Disease relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCaseQuery The current query, for fluid interface
     */
    public function joinDisease($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Disease');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Disease');
        }

        return $this;
    }

    /**
     * Use the Disease relation Disease object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \DiseaseQuery A secondary query class using the current class as primary query
     */
    public function useDiseaseQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinDisease($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Disease', '\DiseaseQuery');
    }

    /**
     * Filter the query by a related \Town object
     *
     * @param \Town|ObjectCollection $town The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildCaseQuery The current query, for fluid interface
     */
    public function filterByTown($town, $comparison = null)
    {
        if ($town instanceof \Town) {
            return $this
                ->addUsingAlias(CaseTableMap::COL_TOWN_ID, $town->getId(), $comparison);
        } elseif ($town instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CaseTableMap::COL_TOWN_ID, $town->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByTown() only accepts arguments of type \Town or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Town relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCaseQuery The current query, for fluid interface
     */
    public function joinTown($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Town');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Town');
        }

        return $this;
    }

    /**
     * Use the Town relation Town object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \TownQuery A secondary query class using the current class as primary query
     */
    public function useTownQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinTown($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Town', '\TownQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildCase $case Object to remove from the list of results
     *
     * @return $this|ChildCaseQuery The current query, for fluid interface
     */
    public function prune($case = null)
    {
        if ($case) {
            $this->addUsingAlias(CaseTableMap::COL_ID, $case->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the case table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CaseTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CaseTableMap::clearInstancePool();
            CaseTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CaseTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CaseTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            CaseTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            CaseTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // CaseQuery
