source("shared/sentiment.r")
source("all/functions.r")
source("shared/color.r")

concatNameTweets <- function(name, tweets){
  return(paste(name, " (", tweets, " tweets)", sep = ''))
}

query <- "select JAIR_BOLSONARO, CIRO_GOMES, GERALDO_ALCKMIN, MARINA_SILVA, MANUELA_DAVILA, JOAO_AMOEDO from V_QUANTIDADE_TWEETS_CANDIDATO"

res <- dbSendQuery(con, query)

totalTweets <- fetch(res, -1)

#get sentiment

sentimentBolsonaro <- groupAndCount(getSentiment(c('jairbolsonaro', 'bolsonaro', 'bolsonaro2018', 'jairbolsonaropresidente', 'bolsonaropresidente')))

sentimentCiro <- groupAndCount(getSentiment(c('cirogomes2018', 'ciro2018', 'cirogomes', 'ciropresidente', 'cirogomespresidente')))

sentimentAlckmin <- groupAndCount(getSentiment(c('alckmin', 'geraldoalckmin', 'alckmin2018', 'alckminpresidente', 'geraldoalckminpresidente')))

sentimentMarina <- groupAndCount(getSentiment(c('marina2018', 'marinasilva', 'marinasilva2018', 'marinapresidente', 'cirogomespresidente')))

sentimentManuela <- groupAndCount(getSentiment(c('manueladavila', 'manueladavila2018', 'manuela2018', 'manueladavilapresidente', 'manuelapresidente')))

sentimentAmoedo <- groupAndCount(getSentiment(c('joaoamoedo', 'joaoamoedo2018', 'amoedo2018', 'joaoamoedopresidente', 'amoedopresidente', 'JoaoAmoedoNaJovemPan')))

sentiments <- matrix(c(sentimentBolsonaro, sentimentCiro, sentimentAlckmin, sentimentMarina, sentimentManuela, sentimentAmoedo), nrow = 3, byrow = FALSE)

ruim <- sentiments[1, ]
neutro <- sentiments[2, ]
bom <- sentiments[3, ]

precandidatos = c(concatNameTweets('Jair Bolsonaro', totalTweets$JAIR_BOLSONARO), concatNameTweets('Ciro Gomes', totalTweets$CIRO_GOMES), 
                  concatNameTweets('Geraldo Alckmin', totalTweets$GERALDO_ALCKMIN), concatNameTweets('Marina Silva', totalTweets$MARINA_SILVA), 
                  concatNameTweets('Manuela D\'\u00c1vila', totalTweets$MANUELA_DAVILA), concatNameTweets('Jo\u00e3o Amo\u00eado', totalTweets$JOAO_AMOEDO))

p <- plot_ly(x = precandidatos, y = ruim, type = 'bar', 
             name = 'Ruim', marker = list(color = red), text = paste(round(ruim, 2), '%', sep = ''), textposition = 'auto') %>%
  add_trace(y = neutro, name = 'Neutro', marker = list(color = orange), text = paste(round(neutro, 2), '%', sep = ''), textposition = 'auto') %>%
  add_trace(y = bom, name = 'Bom', marker = list(color = green), text = paste(round(bom, 2), '%', sep = ''), textposition = 'auto') %>%
  layout(title = 'An\u00e1lise de Sentimento por Pr\u00e9-candidato', yaxis = list(title = '%'), xaxis = list(title = 'Pr\u00e9-candidatos'), barmode = 'stack')