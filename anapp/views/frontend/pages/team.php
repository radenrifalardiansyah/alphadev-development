<div class="flex flex-col justify-center items-center bg-cover py-24">
    <div class="text-gray-100 text-center px-8 md:px-0 md:w-1/2 pb-5">
        <h1 class="text-4xl md:text-6xl text-purple-900">Temui Tim</h1>
        <div class="border-b-2 md:w-96 mx-auto border-dotted border-lime-800 my-4 bb"></div>
        <p class="text-gray-500 md:px-24">Dengan lebih dari 5 tahun pengalaman industri kesehatan dan
            kecantikan, Tim Koporat kami siap melayani Anda.
        </p>
    </div>
</div>

<div class="container my-8 mx-auto text-gray-500">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-0 my-8 mx-25">
        <div class="flex flex-col items-center" x-data="{ open: false }">
            <a @click="open = true" class="cursor-pointer"><img class="w-72 text-center" alt='bob' src="<?= FE_IMG_PATH ?>fgfjhgk.PNG" /></a>
            <div class="justify-start w-40">
                <p class="font-medium pt-5">Suilam Theng</p>
                <p class="text-xs">Direktur Perusahaan</p>
            </div>
            <div class="border-b-2 w-40 border-dotted border-lime-800 my-1 bb"></div>
        </div>
        <div class="flex flex-col items-center" x-data="{ open: false }">
            <a @click="open = true" class="cursor-pointer"><img class="w-72 text-center" alt='bob' src="<?= FE_IMG_PATH ?>jlkljklh.PNG" /></a>
            <div class="justify-start w-40">
                <p class="font-medium pt-5">Kevin Wu</p>
                <p class="text-xs">CEO</p>
            </div>
            <div class="border-b-2 w-40 border-dotted border-lime-800 my-1 bb"></div>
        </div>
        <div class="flex flex-col items-center" x-data="{ open: false }">
            <a @click="open = true" class="cursor-pointer"><img class="w-72 text-center" alt='bob' src="<?= FE_IMG_PATH ?>jkhkhlk.PNG" /></a>
            <div class="justify-start w-40">
                <p class="font-medium pt-5">Anton</p>
                <p class="text-xs">Komisaris</p>
            </div>
            <div class="border-b-2 w-40 border-dotted border-lime-800 my-1 bb"></div>
        </div>
    </div>
</div>