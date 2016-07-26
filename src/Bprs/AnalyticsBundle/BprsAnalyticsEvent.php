<?php

namespace Bprs\AnalyticsBundle;

final class BprsAnalyticsEvent
{
    /**
     * thrown each time you want to write analytics
     * to the system.
     *
     * The event listener receives an
     * Bprs\AnalyticsBundle\Event\CreateAnalyticsEvent instance.
     *
     * @var string
     */
    const BPRS_AN_CREATE = 'bprs_analytics.create';

}
