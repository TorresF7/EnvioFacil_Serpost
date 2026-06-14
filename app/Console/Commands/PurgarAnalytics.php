<?php

namespace App\Console\Commands;

use App\Models\AnalyticsEvent;
use Illuminate\Console\Command;

class PurgarAnalytics extends Command
{
    protected $signature = 'analytics:purgar {--dias=90 : Días de eventos a conservar}';

    protected $description = 'Borra eventos de analítica de UX anteriores a N días (retención de datos).';

    public function handle(): int
    {
        $dias = max(1, (int) $this->option('dias'));
        $limite = now()->subDays($dias);

        $borrados = AnalyticsEvent::where('occurred_at', '<', $limite)->delete();

        $this->info("Purgados {$borrados} eventos anteriores a {$limite->toDateString()} (> {$dias} días).");

        return self::SUCCESS;
    }
}
