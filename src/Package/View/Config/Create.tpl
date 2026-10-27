{{$response = Package.Raxon.Email:Config:create(
    flags(),
    options()
)}}
{{$response|>object:'json'}}