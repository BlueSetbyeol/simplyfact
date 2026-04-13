import { useState } from "react"
import { Button, TextField, styled } from "@mui/material"
import { CloudUploadIcon } from "lucide-react"
import AppLayout from "@/layouts/AppLayout"


{/* Composant input caché visuellement pour le téléchargement de fichiers. */}
const VisuallyHiddenInput = styled('input')({
    clip: 'rect(0 0 0 0)',
    clipPath: 'inset(50%)',
    height: 1,
    overflow: 'hidden',
    position: 'absolute',
    whiteSpace: 'nowrap',
    width: 1,
})

export default function MealForm() {

    {/* Ces trois constantes sont utilisées pour afficher le nombre de repas, le montant total et le remboursement total.*/}
    const [mealNumber, setMealNumber] = useState(0)
    const [totalAmount, setTotalAmount] = useState(0)
    const totalRefund = Math.min(totalAmount, 25)

    {/*Cette constante est utilisée pour stocker le document de preuve.*/}
    const [proofDocument, setProofDocument] = useState<File[]>([])

    return(
        <AppLayout>
            <div className="bg-white rounded-2xl border border-gray-200 p-6 w-full max-w-xl">

                <h1 className="text-xl font-medium text-gray-900">Vos repas</h1>
                <p className="text-sm text-gray-500 mt-1 mb-6">Renseignez vos dépenses de repas effectuées lors de votre déplacement.</p>

                <hr className="border-gray-100 mb-6"/>

                <div className="flex flex-col gap-5">
                    {/* Ces deux champs permettent à l'utilisateur de saisir le nombre de repas et le montant total dépensé. */}
                    <TextField
                        label="Nombre de repas"
                        type="number"
                        value={mealNumber}
                        onChange={(e) => setMealNumber(Number(e.target.value))}
                        fullWidth
                    />
                    <TextField
                        label="Montant total"
                        type="number"
                        value={totalAmount}
                        onChange={(e) => setTotalAmount(Number(e.target.value))}
                        fullWidth
                    />
                </div>  

                <hr className="border-gray-100 my-6"/>

                <div className= "bg-gray-50 rounded-xl p-4 flex justify-between items-center mb-6">
                    {/* Cette section affiche le montant total remboursé, qui est calculé automatiquement en fonction du montant total saisi par l'utilisateur. Le remboursement est plafonné à 25 €. */}
                    <div>
                        <p className= "text-sm text-gray-500">Total remboursé</p>
                        <p className= "text-xs text-gray-400 mt-1">Calculé automatiquement</p>
                    </div>
                    <div className="text-right">
                        <p className= "text-2xl font-medium text-gray-900">{totalRefund}€</p>
                        <p className= "text-xs text-gray-400 mt-1">Plafond : 25 €</p>
                    </div>
                </div>

                <div>
                    <Button 
                        component="label"
                        role={undefined}
                        variant="outlined"
                        fullWidth
                        startIcon={<CloudUploadIcon/>}
                        sx={{
                                color: '#2D6A2D',
                                borderColor: '#2D6A2D',
                                '&:hover': {
                                    borderColor: '#1F4F1F',
                                    backgroundColor: '#F0F7F0'
                                }
                            }}
                        
                    >
                        Document justificatif
                        {/* Ce champ de saisie est caché visuellement, mais il est accessible via le bouton "Document justificatif". Lorsque l'utilisateur clique sur ce bouton, il peut sélectionner un fichier à télécharger. Le fichier sélectionné est ensuite stocké dans l'état `proofDocument`. */}
                        <VisuallyHiddenInput
                            type="file"
                            onChange={(e) => {
                                const files = Array.from(e.target.files || [])
                                setProofDocument(prev => [...prev, ...files])
                            }}
                        />
                    </Button>
                    {proofDocument.length > 0 ? (
                        proofDocument.map((file, index) => (
                            <p key={index} className="text-sm text-gray-500 mt-1">{file.name}</p>
                        ))
                    ) : (
                        <p className= "text-sm text-gray-500 mt-2">Aucun document sélectionné</p>
                    )}
                </div>

                <Button variant="contained" fullWidth className="!mt-5" sx={{ backgroundColor: '#2D6A2D', '&:hover': { backgroundColor: '#1F4F1F'}}}>Suivant</Button>

            </div>
        </AppLayout>
        
    )

}