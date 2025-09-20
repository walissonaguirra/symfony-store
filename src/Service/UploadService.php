<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadService
{
    public function __construct(
        private string $uploadDir
    )
    {
        //
    }

    /**
     * @param UploadedFile[] $files Array de arquivos enviados
     * @param string $targetFolder Pasta de destino para os uploads
     */
    public function upload(array|UploadedFile $files, string $targerFolder)
    {
        if (is_array($files)) {
            foreach ($files as $file) {
                $this->move($file, $targerFolder);
            }
        } else {
            $this->move($files, $targerFolder);
        }
    }

    private function move(UploadedFile $file, string $targerFolder): void
    {
        $file->move(
            $this->uploadDir . '/' . $targerFolder,
            $this->makeFileName($file)
        );
    }

    private function makeFileName(UploadedFile $file): string
    {
        return sha1($file->getClientOriginalName()) . uniqid() . '.' . $file->guessExtension();
    }
}
