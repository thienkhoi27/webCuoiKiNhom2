const s=document.getElementById("search");s.addEventListener("keydown",e=>{e.keyCode===13&&e.preventDefault()});s.addEventListener("keyup",()=>{const e=s.value;$.ajax({type:"GET",url:"/search",data:{_token:$("input[name=_token]").val(),search:e},success:function(a){let n=document.querySelector(".transaction-container");n.innerHTML="",a.length===0?n.innerHTML='<span class="mt-10 text-md font-semibold text-center text-gray-500 w-full">No transactions found</span>':a.forEach(t=>{n.innerHTML+=`
                        <a href="expense/${t.id}">
                            <div class="border-solid border-2 border-[#EEEEEE] px-4 py-2 rounded-2xl">
                                <div class="flex justify-between items-center">
                                    <div class="">
                                        <h1 class="text-md lg:text-lg font-bold mb-2">${t.expense}</h1>
                                        <span class="text-sm lg:text-md font-semibold">${new Date(t.date).toLocaleDateString("en-GB",{day:"2-digit",month:"short",year:"numeric"})}</span>
                                    </div>
                                    <span class="text-right text-md lg:text-xl font-bold">${new Intl.NumberFormat("de-DE").format(t.total)}</span>
                                </div>
                            </div>
                        </a>`})}})});
