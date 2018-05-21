library(RMySQL)
library(tm)
library(graph)
library(magrittr)
library(plotly)

con <- dbConnect(MySQL(), user='homestead', password='secret', dbname='tweets', host='localhost', port = 33060)
res <- dbSendQuery(con, "select * from V_QUANTIDADE_TWEETS_CANDIDATO")
result <- fetch(res, n = -1)
attach(result)

cores <- c("red", "orange", "blue", "yellow", "green", "gray")
pre_candidatos <- c("Geraldo Alckmin", "Jair Bolsonaro", "Manuela D'Ávila", "Marina Silva", "Ciro Gomes", "João Amoêdo")
data <- c(GERALDO_ALCKMIN, JAIR_BOLSONARO, MANUELA_DAVILA, MARINA_SILVA, CIRO_GOMES, JOAO_AMOEDO)
labels <- paste(round(data/sum(data)*100, 4), '%', sep = '')

dataFrame <- data.frame("precandidato"=pre_candidatos, data)

result <- dataFrame[,c('precandidato', 'data')]

p <- plot_ly(dataFrame, labels = ~precandidato, values = ~data, type = 'pie') %>% layout(title = 'Tweets por Pré-candidato',
         xaxis = list(showgrid = FALSE, zeroline = FALSE, showticklabels = FALSE),
         yaxis = list(showgrid = FALSE, zeroline = FALSE, showticklabels = FALSE))

legend("topright", pre_candidatos, cex=2, bty="n", fill=cores)
