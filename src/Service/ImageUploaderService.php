<?php

namespace App\Service;

use Aws\S3\S3Client;
use Aws\Credentials\Credentials;
use Aws\S3\Exception\S3Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class ImageUploaderService
{
    private $awsRegion;
    private $awsAccessKeyId;
    private $awsSecretAccessKey;
    private $awsS3Bucket;

    public function __construct(
        private SluggerInterface $sluggerInterface,
        private ParameterBagInterface $parameterBag
    ) {
        // accès variables dans le constructeur
        $this->awsRegion = $this->parameterBag->get('aws_region');
        $this->awsAccessKeyId = $this->parameterBag->get('aws_access_key_id');
        $this->awsSecretAccessKey = $this->parameterBag->get('aws_secret_access_key');
        $this->awsS3Bucket = $this->parameterBag->get('aws_s3_bucket');
    }

    public function handleImageUpload(UploadedFile $image): string
    {
        $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        $nameSlugged = $this->sluggerInterface->slug($originalName);
        $fileName = $nameSlugged . '-' . uniqid() . '.' . $image->guessExtension();
        try {
            $sha256 = hash_file('sha256', $image->getRealPath());
            $credentials = new Credentials($this->awsAccessKeyId, $this->awsSecretAccessKey);
            $s3 = new S3Client([
                'version' => 'latest',
                'region' => $this->awsRegion,
                'credentials' => $credentials
            ]);

            $s3->putObject([
                'Bucket' => $this->awsS3Bucket,
                'Key' => $fileName,
                'Body' => $image,
                'SourceFile' => $image->getRealPath(),
                'ContentType' => $image->getMimeType(),
                'ContentSHA256' => $sha256,
            ]);

            return $s3->getObjectUrl($this->awsS3Bucket, $fileName);
        } catch (S3Exception $e) {

            dd($e->getAwsErrorMessage());
        }
    }

    public function handleImageUpdate(string $oldImageUrl, UploadedFile $newImage): string
    {
        // Supprime ancienne image sur S3
        $this->handleImageDelete($oldImageUrl);

        // service upload nouvelle image
        return $this->handleImageUpload($newImage);
    }

    public function handleImageDelete(string $imageUrl): void
    {
        // nom du fichier URL
        $urlSegments = explode('/', $imageUrl);
        $fileName = array_pop($urlSegments);

        // Supprime fichier sur S3
        $this->deleteImage($fileName);
    }

    private function deleteImage(string $fileName): void
    {
        try {
            $credentials = new Credentials($this->awsAccessKeyId, $this->awsSecretAccessKey);
            $s3 = new S3Client([
                'version' => 'latest',
                'region' => $this->awsRegion,
                'credentials' => $credentials
            ]);

            $s3->deleteObject([
                'Bucket' => $this->awsS3Bucket,
                'Key' => $fileName,
            ]);
        } catch (S3Exception $e) {

            dd($e->getMessage());
        }
    }
}
