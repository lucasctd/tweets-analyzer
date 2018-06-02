source("shared/feeling.r")

tonumber <- function(x){
  return(as.numeric(levels(x))[x])
}

calcPercentage <- function(qtd, total){
  return(qtd / total * 100)
}

groupAndCount <- function(df){
  ltdot25 <- with(df, c(sum(tonumber(df$score) <= .25 )))
  ltdot5 <- with(df, c(sum(tonumber(df$score) <= .5 & tonumber(df$score) > .25)))
  ltdot75 <- with(df, c(sum(tonumber(df$score) <= .75 & tonumber(df$score) > .5)))
  gt75 <- with(df, c(sum(tonumber(df$score) > .75 )))
  
  return(c(ltdot25, ltdot5, ltdot75, gt75))
}

feelingBolsonaro <- getFeeling('jairbolsonaro', 'bolsonaro', 'bolsonaro2018')

feelingCiro <- getFeeling('cirogomes2018', 'ciro2018', 'cirogomes')

valuesBolsonaro <- groupAndCount(feelingBolsonaro)

valuesCiro <- groupAndCount(feelingCiro)

pessimo <- c(valuesBolsonaro[[1]], valuesCiro[[1]])
ruim <- c(valuesBolsonaro[[2]], valuesCiro[[2]])
bom <- c(valuesBolsonaro[[3]], valuesCiro[[3]])
otimo <- c(valuesBolsonaro[[4]], valuesCiro[[4]])

pessimoInfo <- paste(lapply(pessimo, function (x) round(calcPercentage(x, length(feelingBolsonaro$score)), 4)), '%', sep='')
ruimInfo <- paste(lapply(pessimo, function (x) round(calcPercentage(x, length(feelingBolsonaro$score)), 4)), '%', sep='')
bomInfo <- paste(lapply(pessimo, function (x) round(calcPercentage(x, length(feelingBolsonaro$score)), 4)), '%', sep='')
otimoInfo <- paste(lapply(pessimo, function (x) round(calcPercentage(x, length(feelingBolsonaro$score)), 4)), '%', sep='')

data <- data.frame(precandidatos = c('Jair Bolsonaro', 'Ciro Gomes'), pessimo, ruim, bom, bom, otimo)

p <- plot_ly(data, x = ~precandidatos, y = ~pessimo, type = 'bar', 
             name = 'P\u00e9ssimo', marker = list(color = '#FF6347')) %>%
  add_trace(y = ~ruim, name = 'Ruim', marker = list(color = '#orange')) %>%
  add_trace(y = ~bom, name = 'Bom', marker = list(color = '00FFFF')) %>%
  add_trace(y = ~otimo, name = '\u00d3timo', marker = list(color = '#7FFF00')) %>%
  layout(title = 'An\u00e1lise de Sentimento por Pr\u00e9-candidato', yaxis = list(title = 'N\u00famero de Tweets'), xaxis = list(title = 'Pré-candidatos'), barmode = 'stack')

#inverse

dataInverse <- data.frame(feelings = c('Péssimo', 'Ruim', 'Bom', 'Ótimo'), valuesBolsonaro, valuesCiro)

p <- plot_ly(data, x = ~feelings, y = ~valuesBolsonaro, type = 'bar', 
             name = 'Jair Bolsonaro', marker = list(color = '#FF6347')) %>%
  add_trace(y = ~ruim, name = 'Ciro Gomes', marker = list(color = '#orange')) %>%
  layout(title = 'An\u00e1lise de Sentimento por Pr\u00e9-candidato', yaxis = list(title = 'N\u00famero de Tweets'), xaxis = list(title = 'Sentimentos'), barmode = 'stack')


