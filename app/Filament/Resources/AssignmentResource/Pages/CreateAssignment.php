<?php

namespace App\Filament\Resources\AssignmentResource\Pages;

use App\Filament\Resources\AssignmentResource;
use App\Filament\Resources\SurveyResource;
use App\Http\Controllers\AssignmentPDFController;
use App\Models\Assignment;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class CreateAssignment extends CreateRecord
{
    protected static string $resource = AssignmentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function afterCreate(): void
    {
        $assignment = $this->record;

        $debitur = $assignment->contracts->pemberi_tugas;

        $surveyor = $assignment->surveyors->name;

        $noPenugasan = $assignment->no_penugasan;

        $recipients = User::where('role', 'surveyor')->get();

        foreach ($recipients as $recipient) {
            $recipient->notify(
                Notification::make()
                    ->title('Surat tugas survey')
                    ->warning()
                    ->body("Terdapat surat tugas dari $debitur untuk surveyor $surveyor, dengan nomor penugasan KJPP$noPenugasan. silahkan dilanjutkan ke proses survey")
                    ->actions([
                        Action::make('View')
                            ->button()
                            ->url(route('assignment.pdf', ['id' => $assignment->id]))
                            ->openUrlInNewTab(),
                        Action::make('Survey')
                            ->button()
                            ->url(SurveyResource::getUrl('create'))
                            ->openUrlInNewTab()
                    ])
                    ->toDatabase($recipients)
            );
        }
    }
}
