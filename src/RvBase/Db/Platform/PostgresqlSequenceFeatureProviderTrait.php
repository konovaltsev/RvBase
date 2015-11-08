<?php

namespace RvBase\Db\Platform;

use Zend\Db\TableGateway\Feature\SequenceFeature;

/**
 * Provides correct sequence feature for postgresql non-public schema sequence name
 * (to fix a bug in ZF2 SequenceFeature)
 *
 * @package RvBase\Db\Platform
 */
trait PostgresqlSequenceFeatureProviderTrait
{
    protected function getSequenceFeature($primaryKeyField, $sequenceName, $sequenceSchema = null)
    {
        if($sequenceSchema)
        {
            $sequenceName = sprintf('%s"."%s', $sequenceSchema, $sequenceName);
        }

        return new SequenceFeature($primaryKeyField, $sequenceName);
    }
}
