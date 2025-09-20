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
    public function upload(array|UploadedFile $files, string $targerFolder): array|string
    {
        if (is_array($files)) {
            $filesUploaded = [];
            foreach ($files as $file) {
                $filesUploaded[] = $this->move($file, $targerFolder);
            }

            return $filesUploaded;
        } else {
            return $this->move($files, $targerFolder);
        }
    }

    private function move(UploadedFile $file, string $targerFolder): string
    {
        $fileName = $this->makeFileName($file);
        $file->move(
            $this->uploadDir . '/' . $targerFolder,
            $fileName
        );

        return $fileName;
    }

    private function makeFileName(UploadedFile $file): string
    {
        return sha1($file->getClientOriginalName()) . uniqid() . '.' . $file->guessExtension();
    }
}
