<?php
require_once dirname(__FILE__) . '/base_log.php';
require_once dirname(__FILE__) . '/../request_context.php';

/**
 * Elastic Common Schema: A commonly used logging format.
 * It's especially useful when used in the classic Kibana/ElasticSearch logging stack.
 *
 * Version chosen based to align with the version of ES we're running.
 * https://www.elastic.co/guide/en/ecs/8.17/ecs-field-reference.html
 */
class EcsLog extends BaseLog
{

    function format($type, $message)
    {
        $utc = (new DateTimeImmutable())->setTimezone(new DateTimeZone('UTC'));
        $timestamp = $utc->format('Y-m-d\TH:i:s.u\Z');

        $entry = array(
            '@timestamp' => $timestamp,
            'log.level' => $type,
            'message' => $message,
            'transaction.id' => RequestContext::correlationId(),
            'ecs.version' => '8.17.0',
        );

        $userId = RequestContext::userId();
        if ($userId !== null) {
            $entry['user.id'] = $userId;
        }

        $username = RequestContext::username();
        if ($username !== null) {
            $entry['user.name'] = $username;
        }

        return json_encode($entry, JSON_UNESCAPED_SLASHES) . "\n";
    }
}
