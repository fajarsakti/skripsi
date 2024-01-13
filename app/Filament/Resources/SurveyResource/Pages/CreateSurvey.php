<?php

namespace App\Filament\Resources\SurveyResource\Pages;

use App\Filament\Resources\SurveyResource;
use App\Http\Controllers\OrderPDFController;
use App\Models\Survey;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Illuminate\Support\Facades\Log;
use App\Models\Contract;
use App\Models\Surveyor;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;

class CreateSurvey extends CreateRecord
{
    protected static string $resource = SurveyResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['id'] = auth()->id();

        return $data;
    }

    public function afterCreate(): void
    {
        $survey = $this->record;

        $contract = Contract::find($survey->contract_id);

        $pemberiTugas = $contract->pemberi_tugas;
        $contractId = $survey->contract_id;
        $surveyId = $survey->id;
        $surveyorId = $survey->surveyors_id;
        $surveyor = Surveyor::find($surveyorId);
        $surveyorName = $surveyor->name;
        $tanggalSurvey = $survey->tanggal_survey;

        $recipients = User::where('role', 'admin')->get();

        foreach ($recipients as $recipient) {
            $recipient->notify(
                Notification::make()
                    ->title('Telah dilakukan survey')
                    ->success()
                    ->body("Survey untuk order $contractId dengan pemberi tugas $pemberiTugas sudah diselesaikan dengan ID survey $surveyId dan oleh surveyor $surveyorName pada $tanggalSurvey ")
                    ->actions([
                        Action::make('View')
                            ->button()
                            ->url(SurveyResource::getUrl('view', ['record' => $survey]))
                            ->openUrlInNewTab()
                    ])
                    ->toDatabase($recipients)
            );
        }
    }
}
