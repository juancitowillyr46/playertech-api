<?php
declare(strict_types=1);
namespace App\Modules\Player\Presentation\Http\Document\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
readonly class CreatePlayerDocumentRequest
{
    private function __construct(public string $documentType, public UploadedFile $file, public ?string $observations) {}
    public static function fromRequest(Request $request): self
    {
        $file = $request->files->get('file');
        if (!$file instanceof UploadedFile) { throw new \InvalidArgumentException('El archivo es obligatorio.'); }
        $type = trim((string) $request->request->get('documentType', ''));
        if ('' === $type) { throw new \InvalidArgumentException('El tipo de documento es obligatorio.'); }
        return new self($type, $file, $request->request->get('observations'));
    }
}
